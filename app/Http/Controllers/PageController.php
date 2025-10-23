<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Event;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Display the form builder page
     */
    public function index($id)
    {
        $event = Event::with('school')->find($id);

        if (!$event) {
            return redirect()->route('dashboard')->with('error', 'Event not found.');
        }

        $school_uuid = $event->school->uuid;
        $user = auth()->user();

        $query = Page::with(['school', 'event'])
            ->where('event_id', $id);

        if ($user->hasRole('super-admin')) {
            // No additional filters for super-admin
        } elseif ($user->hasRole('school-admin')) {
            $query->where('school_id', $user->school_id);
        } else {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        $pages = $query->latest()->paginate(10);
        return view('dashboard.events.advertisements', compact('pages', 'id', 'school_uuid'));
    }

    // Show edit form for a specific page
    public function edit($id)
    {
        $page = Page::with(['school', 'event'])->findOrFail($id);
        $events = Event::all();

        return view('dashboard.events.advertisements_edit', compact('page', 'events'));
    }

    // Update existing page
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'form_data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $page = Page::findOrFail($id);

            $formData = json_decode($request->form_data, true);
            $sanitizedData = $this->sanitizeFormData($formData);

            // Keep same slug or regenerate if name changed (optional)
            $slug = $page->slug;
            if ($page->name !== $request->name) {
                $slug = $this->generateUniqueSlug($request->name, $page->id);
            }

            $page->update([
                'name' => $request->name,
                'event_id' => $request->event_id,
                'slug' => $slug,
                'structure' => $sanitizedData,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully!',
                'page_id' => $page->id,
                'slug' => $page->slug,
                'redirect_url' => route('pages.show', [$page->slug, $page->uuid])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update page: ' . $e->getMessage()
            ], 500);
        }
    }


    public function create(string $school_uuid, string $event_id)
    {
        // $school = School::where('uuid', $school_uuid)->firstOrFail();
        $event = Event::where('id', $event_id)->firstOrFail();

        // Optional: verify that the event belongs to the school
        // if ($event->school_id !== $school->id) {
        //     abort(403, 'Event does not belong to this school.');
        // }

        return view('dashboard.events.advertisement_template', compact('event'));
    }

    /**
     * Store the form data securely
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'school_id' => 'required|exists:schools,id',
            'form_data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $school = School::find($request->school_id);
            if (!$school) {
                throw new \Exception('School not found');
            }

            $formData = json_decode($request->form_data, true);

            // Process images and store them
            $processedData = $this->processFormDataImages($formData, $school);

            // Sanitize the structure
            $sanitizedData = $this->sanitizeFormData($processedData);

            // Generate unique slug
            $slug = $this->generateUniqueSlug($request->name);

            // Create the page
            $page = Page::create([
                'uuid' => (string) Str::uuid(),
                'event_id' => $request->event_id,
                'school_id' => $request->school_id,
                'name' => $request->name,
                'slug' => $slug,
                'structure' => $sanitizedData,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'page_id' => $page->id,
                'slug' => $page->slug,
                'redirect_url' => route('pages.show', [$page->slug, $page->uuid])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific page
     */
    public function show($slug, string $page_uuid)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->structure = $this->prepareStructureForDisplay($page->structure);

        return view('dashboard.events.advertisement_show', compact('page'));
    }

    /**
     * Generate unique slug for page
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = Page::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = Page::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Process base64 images and store them in website disk
     */
    private function processFormDataImages($data, $school)
    {
        if (!is_array($data)) {
            return $data;
        }

        $folderName = Str::slug($school->name, '-');
        $processedImages = []; // Track processed images to avoid duplicates

        // Process elements array
        if (isset($data['elements']) && is_array($data['elements'])) {
            foreach ($data['elements'] as &$element) {
                if (isset($element['content']) && is_array($element['content'])) {

                    // Process images array in content
                    if (isset($element['content']['images']) && is_array($element['content']['images'])) {
                        foreach ($element['content']['images'] as &$image) {
                            if ($this->isBase64Image($image) && !isset($processedImages[$image])) {
                                $savedPath = $this->saveBase64Image($image, $folderName);
                                if ($savedPath) {
                                    $processedImages[$image] = $savedPath;
                                    $image = $savedPath;
                                }
                            } elseif (isset($processedImages[$image])) {
                                // Use already processed image
                                $image = $processedImages[$image];
                            }
                        }
                    }

                    // Process individual content fields
                    foreach ($element['content'] as $key => &$value) {
                        if (is_string($value) && $this->containsBase64Image($value)) {
                            $value = $this->processHtmlImages($value, $folderName, $processedImages);
                        }
                    }

                    // Process src fields (for banner, image types)
                    if (
                        isset($element['content']['src']) &&
                        $this->isBase64Image($element['content']['src']) &&
                        !isset($processedImages[$element['content']['src']])
                    ) {
                        $savedPath = $this->saveBase64Image($element['content']['src'], $folderName);
                        if ($savedPath) {
                            $processedImages[$element['content']['src']] = $savedPath;
                            $element['content']['src'] = $savedPath;
                        }
                    } elseif (isset($element['content']['src']) && isset($processedImages[$element['content']['src']])) {
                        $element['content']['src'] = $processedImages[$element['content']['src']];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Check if string is a base64 image
     */
    private function isBase64Image($string)
    {
        if (!is_string($string)) return false;

        return strpos($string, 'data:image/') === 0 && strpos($string, 'base64,') !== false;
    }

    /**
     * Check if HTML contains base64 images
     */
    private function containsBase64Image($html)
    {
        return is_string($html) && strpos($html, 'data:image/') !== false;
    }

    /**
     * Save base64 image to website disk and return path
     */
    private function saveBase64Image($base64Image, $folderName)
    {
        try {
            // Extract image data
            preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
            $imageType = $matches[1] ?? 'jpeg';
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);

            if (!$imageData) {
                throw new \Exception('Invalid base64 image data');
            }

            // Generate unique filename
            $filename = Str::uuid() . '.' . $imageType;

            // Define the path structure
            $filePath = "school/{$folderName}/page/images/{$filename}";

            // Store image using website disk
            Storage::disk('website')->put($filePath, $imageData);

            return $filePath;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Process HTML content and replace base64 images with stored images
     */
    private function processHtmlImages($html, $folderName, &$processedImages = [])
    {
        // Find all base64 images in the HTML
        preg_match_all('/src="(data:image\/[^"]+)"/', $html, $matches);

        if (empty($matches[1])) {
            return $html;
        }

        foreach ($matches[1] as $base64Image) {
            if ($this->isBase64Image($base64Image)) {
                if (isset($processedImages[$base64Image])) {
                    // Use already processed image
                    $html = str_replace($base64Image, $processedImages[$base64Image], $html);
                } else {
                    // Process new image
                    $imagePath = $this->saveBase64Image($base64Image, $folderName);
                    if ($imagePath) {
                        $processedImages[$base64Image] = $imagePath;
                        $html = str_replace($base64Image, $imagePath, $html);
                    }
                }
            }
        }

        return $html;
    }

    /**
     * Sanitize form data while preserving image paths
     */
    private function sanitizeFormData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Skip sanitization for image paths and images arrays
                if ($key === 'images' || $this->isImagePath($value)) {
                    continue;
                }
                $data[$key] = $this->sanitizeFormData($value);
            }
            return $data;
        } elseif (is_string($data)) {
            return $this->smartSanitize($data);
        } else {
            return $data;
        }
    }

    /**
     * Check if value is an image path from website disk
     */
    private function isImagePath($value)
    {
        if (!is_string($value)) return false;

        return strpos($value, 'school/') === 0 &&
            strpos($value, '/page/images/') !== false &&
            preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $value);
    }

    /**
     * Smart sanitization that preserves image paths and safe HTML
     */
    private function smartSanitize($string)
    {
        if (empty($string) || $this->isImagePath($string)) {
            return $string;
        }

        // Remove any NULL characters
        $string = str_replace("\0", '', $string);

        // For raw-html elements, use more permissive sanitization
        if ($this->isRawHtmlContent($string)) {
            return $this->sanitizeRawHtml($string);
        }

        // List of allowed HTML tags for regular content
        $allowedTags = '<h1><h2><h3><h4><h5><h6><p><br><div><span><strong><b><em><i><u><ul><ol><li><table><tr><td><th><thead><tbody><blockquote><code><pre><img><a>';

        // First, decode any existing HTML entities to work with raw HTML
        $string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove script tags and dangerous content using replacement method
        $string = $this->replaceDangerousScripts($string);

        // Remove dangerous attributes from all tags
        $dangerousAttributes = ['onclick', 'onload', 'onerror', 'onmouseover', 'onmouseout', 'onkeydown', 'onkeypress', 'onkeyup', 'onfocus', 'onblur', 'onchange', 'onsubmit', 'contenteditable', 'data-placeholder', 'spellcheck', 'aria-label', 'data-gramm', 'data-new-gr-c-s-check-loaded', 'gramm'];
        foreach ($dangerousAttributes as $attr) {
            $string = preg_replace('/\s+' . $attr . '\s*=\s*["\'][^"\']*["\']/i', '', $string);
            $string = preg_replace('/\s+' . $attr . '\s*=\s*[^"\'][^>]*/i', '', $string);
        }

        // Remove javascript: and data: protocols from href and src attributes
        $string = preg_replace('/href=["\']javascript:[^"\']*["\']/i', 'href="#"', $string);
        $string = preg_replace('/src=["\']javascript:[^"\']*["\']/i', 'src=""', $string);
        $string = preg_replace('/href=["\']data:[^"\']*["\']/i', 'href="#"', $string);
        // $string = preg_replace('/src=["\']data:[^"\']*["\']/i', 'src=""', $string);

        // Use strip_tags with allowed tags to preserve safe HTML
        $string = strip_tags($string, $allowedTags);

        // Clean up any empty tags that might have been created
        $string = preg_replace('/<([^>]+)>\s*<\/\1>/', '', $string);

        return trim($string);
    }

    /**
     * Replace dangerous scripts and JavaScript code with encoded versions
     */
    private function replaceDangerousScripts($string)
    {
        // Replace script tags and content
        $string = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '&lt;script&gt;$1&lt;/script&gt;', $string);

        // Replace common JavaScript keywords with encoded versions
        $dangerousPatterns = [
            '/\bdocument\b/i' => '00002d',
            '/\bwindow\b/i' => '00003w',
            '/\balert\b/i' => '00004a',
            '/\beval\b/i' => '00005e',
            '/\bsetTimeout\b/i' => '00006s',
            '/\bsetInterval\b/i' => '00007s',
            '/\bfunction\b/i' => '00008f',
            '/\bvar\b/i' => '00009v',
            '/\blet\b/i' => '00010l',
            '/\bconst\b/i' => '00011c',
            '/\bthis\b/i' => '00012t',
            '/\bparent\b/i' => '00013p',
            '/\btop\b/i' => '00014t',
            '/\blocation\b/i' => '00015l',
            '/\bcookie\b/i' => '00016c',
            '/\blocalStorage\b/i' => '00017l',
            '/\bsessionStorage\b/i' => '00018s',
            '/\bfetch\b/i' => '00019f',
            '/\bXMLHttpRequest\b/i' => '00020x',
            '/\bimport\b/i' => '00021i',
            '/\bexport\b/i' => '00022e',
            '/\bfrom\b/i' => '00023f',
            '/\brequire\b/i' => '00024r',
        ];

        foreach ($dangerousPatterns as $pattern => $replacement) {
            $string = preg_replace($pattern, $replacement, $string);
        }

        return $string;
    }

    /**
     * Check if content appears to be raw HTML (contains HTML structure)
     */
    private function isRawHtmlContent($string)
    {
        // Check if it contains HTML tags and structure
        return preg_match('/<(!DOCTYPE|html|head|body|style|meta|link)[^>]*>/i', $string) ||
            preg_match('/<[^>]+\s+style\s*=\s*["\'][^"\']*["\'][^>]*>/i', $string) ||
            (substr_count($string, '<') > 2 && substr_count($string, '>') > 2);
    }

    /**
     * More permissive sanitization for raw HTML content
     */
    private function sanitizeRawHtml($html)
    {
        if (empty($html)) {
            return $html;
        }

        // Remove any NULL characters
        $html = str_replace("\0", '', $html);

        // Decode HTML entities
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Replace dangerous scripts first
        $html = $this->replaceDangerousScripts($html);

        // Remove the most dangerous tags completely (not just encode)
        $dangerousTags = ['iframe', 'object', 'embed', 'form', 'input', 'button', 'select', 'textarea'];
        foreach ($dangerousTags as $tag) {
            $html = preg_replace('/<' . $tag . '\b[^>]*>(.*?)<\/' . $tag . '>/is', '', $html);
            $html = preg_replace('/<' . $tag . '\b[^>]*\/?>/i', '', $html);
        }

        // Remove dangerous event attributes and other risky attributes
        $dangerousAttributes = [
            'onclick',
            'onload',
            'onerror',
            'onmouseover',
            'onmouseout',
            'onkeydown',
            'onkeypress',
            'onkeyup',
            'onfocus',
            'onblur',
            'onchange',
            'onsubmit',
            'contenteditable'
        ];

        foreach ($dangerousAttributes as $attr) {
            $html = preg_replace('/\s+' . $attr . '\s*=\s*["\'][^"\']*["\']/i', '', $html);
            $html = preg_replace('/\s+' . $attr . '\s*=\s*[^"\'][^>]*/i', '', $html);
        }

        // Remove dangerous protocols
        $html = preg_replace('/href=["\']javascript:[^"\']*["\']/i', 'href="#"', $html);
        $html = preg_replace('/src=["\']javascript:[^"\']*["\']/i', 'src=""', $html);
        $html = preg_replace('/href=["\']data:[^"\']*["\']/i', 'href="#"', $html);
        $html = preg_replace('/src=["\']data:[^"\']*["\']/i', 'src=""', $html);

        // Extract only the body content if it's a full HTML document
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $html = $matches[1];
        } elseif (preg_match('/<html[^>]*>(.*?)<\/html>/is', $html, $matches)) {
            $html = $matches[1];
        }

        // Remove head, title, meta, link tags but keep style tags
        $html = preg_replace('/<(head|title|meta|link)\b[^>]*>(.*?)<\/\1>/is', '', $html);
        $html = preg_replace('/<(head|title|meta|link)\b[^>]*\/?>/i', '', $html);

        // Allow most HTML tags but remove the most dangerous ones
        $allowedTags = '<div><span><p><h1><h2><h3><h4><h5><h6><br><hr><ul><ol><li><table><tr><td><th><thead><tbody><blockquote><code><pre><img><a><strong><b><em><i><u><small><sup><sub><style>';
        $html = strip_tags($html, $allowedTags);

        // Clean up any empty tags
        $html = preg_replace('/<([^>]+)>\s*<\/\1>/', '', $html);

        return trim($html);
    }

    /**
     * Prepare structure for display by converting image paths to URLs
     */
    private function prepareStructureForDisplay($structure)
    {
        if (isset($structure['elements']) && is_array($structure['elements'])) {
            foreach ($structure['elements'] as &$element) {
                if (isset($element['content'])) {
                    // Ensure image paths are converted to URLs for display in images array
                    if (isset($element['content']['images']) && is_array($element['content']['images'])) {
                        foreach ($element['content']['images'] as &$image) {
                            if (!empty($image) && is_string($image)) {
                                $image = $this->getCorrectImageUrl($image);
                            }
                        }
                    }

                    // Process src fields for banner and image types
                    if (
                        isset($element['content']['src']) &&
                        is_string($element['content']['src'])
                    ) {
                        $element['content']['src'] = $this->getCorrectImageUrl($element['content']['src']);
                    }

                    // Process ALL content fields that might contain HTML with images
                    foreach ($element['content'] as $key => &$value) {
                        if (is_string($value)) {
                            // Replace website disk paths with full URLs in HTML for ALL fields
                            $value = $this->convertImagePathsToCorrectUrls($value);

                            // Clean and decode content
                            $value = $this->cleanContent($value);
                            $value = $this->decodeSafeContent($value);
                        }
                    }
                }
            }
        }

        return $structure;
    }

    /**
     * Get correct image URL with proper port and protocol
     */
    private function getCorrectImageUrl($path)
    {
        // If it's already a full URL, check if it needs port correction
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $this->ensureCorrectPort($path);
        }

        // If it starts with http, check port
        if (strpos($path, 'http') === 0) {
            return $this->ensureCorrectPort($path);
        }

        // If it's a relative path (starts with school/), convert to asset URL
        if (strpos($path, 'school/') === 0) {
            return asset('website/' . $path);
        }

        // For any stored full URL that might be incorrect
        return $this->ensureCorrectPort($path);
    }

    /**
     * Ensure URL has the correct port (match what asset() generates)
     */
    private function ensureCorrectPort($url)
    {
        $currentBaseUrl = url('/');
        $parsedUrl = parse_url($url);

        // If the URL doesn't match our current base URL, reconstruct it
        if (isset($parsedUrl['host']) && $parsedUrl['host'] === parse_url($currentBaseUrl, PHP_URL_HOST)) {
            // Reconstruct with current base URL to ensure correct port
            $path = $parsedUrl['path'] ?? '';
            $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
            $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

            return $currentBaseUrl . $path . $query . $fragment;
        }

        return $url;
    }

    /**
     * Convert image paths to correct URLs in HTML content
     */
    private function convertImagePathsToCorrectUrls($content)
    {
        if (empty($content) || !is_string($content)) {
            return $content;
        }

        // Replace image paths in src attributes (both quoted formats)
        $content = preg_replace_callback(
            '/src=(["\'])(.*?)\1/i',
            function ($matches) {
                $url = $matches[2];
                $correctUrl = $this->getCorrectImageUrl($url);
                return 'src=' . $matches[1] . $correctUrl . $matches[1];
            },
            $content
        );

        return $content;
    }
    /**
     * Convert image paths to URLs in HTML content
     */
    private function convertImagePathsToUrls($content)
    {
        if (empty($content) || !is_string($content)) {
            return $content;
        }

        // Replace image paths in src attributes
        $content = preg_replace_callback(
            '/src="(school\/[^"]+\.(jpg|jpeg|png|gif|webp))"/i',
            function ($matches) {
                return 'src="' . $this->getImageUrl($matches[1]) . '"';
            },
            $content
        );

        // Also handle single quotes
        $content = preg_replace_callback(
            "/src='(school\/[^']+\.(jpg|jpeg|png|gif|webp))'/i",
            function ($matches) {
                return "src='" . $this->getImageUrl($matches[1]) . "'";
            },
            $content
        );

        return $content;
    }

    /**
     * Get image URL from path
     */
    private function getImageUrl($path)
    {
        return Storage::disk('website')->url($path);
    }

    /**
     * Decode safe content - reverse the encoding we did during sanitization
     */
    private function decodeSafeContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Reverse the encoding patterns - decode back to original words
        $decodingPatterns = [
            '/00002d/' => 'document',
            '/00003w/' => 'window',
            '/00004a/' => 'alert',
            '/00005e/' => 'eval',
            '/00006s/' => 'setTimeout',
            '/00007s/' => 'setInterval',
            '/00008f/' => 'function',
            '/00009v/' => 'var',
            '/00010l/' => 'let',
            '/00011c/' => 'const',
            '/00012t/' => 'this',
            '/00013p/' => 'parent',
            '/00014t/' => 'top',
            '/00015l/' => 'location',
            '/00016c/' => 'cookie',
            '/00017l/' => 'localStorage',
            '/00018s/' => 'sessionStorage',
            '/00019f/' => 'fetch',
            '/00020x/' => 'XMLHttpRequest',
            '/00021i/' => 'import',
            '/00022e/' => 'export',
            '/00023f/' => 'from',
            '/00024r/' => 'require',
        ];

        foreach ($decodingPatterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        // Also decode script tags if they were encoded
        $content = str_replace('&lt;script&gt;', '<script>', $content);
        $content = str_replace('&lt;/script&gt;', '</script>', $content);

        return $content;
    }

    /**
     * Clean content by removing editing attributes
     */
    public static function cleanContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Remove contenteditable and other editing attributes but preserve HTML tags
        $content = preg_replace('/\s+contenteditable\s*=\s*["\'][^"\']*["\']/i', '', $content);
        $content = preg_replace('/\s+data-placeholder\s*=\s*["\'][^"\']*["\']/i', '', $content);
        $content = preg_replace('/\s+spellcheck\s*=\s*["\'][^"\']*["\']/i', '', $content);
        $content = preg_replace('/\s+aria-label\s*=\s*["\'][^"\']*["\']/i', '', $content);

        // Remove Grammarly and other editor-specific attributes
        $content = preg_replace('/\s+data-gramm\s*=\s*["\'][^"\']*["\']/i', '', $content);
        $content = preg_replace('/\s+data-new-gr-c-s-check-loaded\s*=\s*["\'][^"\']*["\']/i', '', $content);
        $content = preg_replace('/\s+gramm\s*=\s*["\'][^"\']*["\']/i', '', $content);

        return trim($content);
    }
}
