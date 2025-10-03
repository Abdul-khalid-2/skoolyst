<?php
// app/Http/Controllers/PageController.php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * Display the form builder page
     */
    public function index()
    {
        $events = Event::all(['id', 'event_name']);
        return view('dashboard.events.advertisement_template', compact('events'));
    }

    /**
     * Store the form data securely
     */
    public function store(Request $request)
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
            // Decode the JSON data
            $formData = json_decode($request->form_data, true);

            // Sanitize the entire structure recursively
            $sanitizedData = $this->sanitizeFormData($formData);

            // Generate slug from name
            $slug = Str::slug($request->name);

            // Check if slug already exists
            $counter = 1;
            $originalSlug = $slug;
            while (Page::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create the page
            $page = Page::create([
                'event_id' => $request->event_id,
                'name' => $request->name,
                'slug' => $slug,
                'structure' => $sanitizedData,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'page_id' => $page->id,
                'slug' => $page->slug,
                'redirect_url' => route('pages.show', $page->slug)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recursively sanitize form data to prevent XSS attacks
     */
    private function sanitizeFormData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
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
     * Smart sanitization that preserves safe HTML but removes dangerous content
     */
    private function smartSanitize($string)
    {
        if (empty($string)) {
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
        $string = preg_replace('/src=["\']data:[^"\']*["\']/i', 'src=""', $string);

        // Use strip_tags with allowed tags to preserve safe HTML
        $string = strip_tags($string, $allowedTags);

        // Clean up any empty tags that might have been created
        $string = preg_replace('/<([^>]+)>\s*<\/\1>/', '', $string);

        // Trim whitespace
        $string = trim($string);

        return $string;
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
     * Display a specific page
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Process the page structure to ensure proper content display
        $page->structure = $this->prepareStructureForDisplay($page->structure);

        return view('dashboard.events.advertisement_show', compact('page'));
    }

    private function prepareStructureForDisplay($structure)
    {
        if (isset($structure['elements']) && is_array($structure['elements'])) {
            foreach ($structure['elements'] as &$element) {
                if (isset($element['content'])) {
                    // For raw-html type, ensure HTML is properly formatted
                    if ($element['type'] === 'raw-html' && isset($element['content']['html'])) {
                        $element['content']['html'] = $this->cleanContent($element['content']['html']);
                        $element['content']['html'] = $this->decodeSafeContent($element['content']['html']);
                    }

                    // For other content types, clean and decode the content
                    foreach ($element['content'] as $key => $value) {
                        if (is_string($value)) {
                            $element['content'][$key] = $this->cleanContent($value);
                            $element['content'][$key] = $this->decodeSafeContent($element['content'][$key]);
                        }
                    }
                }
            }
        }

        return $structure;
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
