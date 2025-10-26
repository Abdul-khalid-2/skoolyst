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

    /**
     * Show the form for creating a new page
     */
    public function create(string $school_uuid, $event_id)
    {
        $event = Event::with('school')->findOrFail($event_id);
        return view('dashboard.events.advertisement_template', compact('event'));
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
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
            $formData = json_decode($request->form_data, true);

            // Process images from base64 to file storage
            $processedData = $this->processImages($formData, $request->school_id);

            // Extract individual element data for separate columns
            $elementData = $this->extractElementData($processedData);

            // Generate unique slug
            $slug = $this->generateUniqueSlug($request->name);

            $page = Page::create([
                'uuid' => Str::uuid(),
                'school_id' => $request->school_id,
                'event_id' => $request->event_id,
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $slug,
                'structure' => $processedData,
                'title' => $elementData['titles'] ?? null,
                'banner' => $elementData['banners'] ?? null,
                'image' => $elementData['images'] ?? null,
                'rich_text' => $elementData['rich_texts'] ?? null,
                'text_left_image_right' => $elementData['text_left_image_right'] ?? null,
                'custom_html' => $elementData['custom_htmls'] ?? null,
                'canvas_elements' => $elementData['canvas_elements'] ?? null,
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
     * Display the specified page
     */
    public function show($slug, string $page_uuid)
    {
        $page = Page::where('slug', $slug)->where('uuid', $page_uuid)->firstOrFail();

        // Prepare data for display - convert image paths to URLs
        $page->structure = $this->prepareForDisplay($page->structure);

        return view('dashboard.events.advertisement_show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit($id)
    {
        $user = auth()->user();
        $page = Page::with(['school', 'event'])->findOrFail($id);
        $events = Event::where('school_id', $page->school_id)->get();

        // Prepare structure for editing
        if (!$page->structure) {
            $page->structure = ['elements' => []];
        } else {
            $page->structure = $this->prepareForDisplay($page->structure);
        }

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

            // Process images from base64 to file storage
            $processedData = $this->processImages($formData, $page->school_id);

            // Extract individual element data for separate columns
            $elementData = $this->extractElementData($processedData);

            // Keep same slug or regenerate if name changed
            $slug = $page->slug;
            if ($page->name !== $request->name) {
                $slug = $this->generateUniqueSlug($request->name, $page->id);
            }

            $page->update([
                'name' => $request->name,
                'description' => $request->description,
                'event_id' => $request->event_id,
                'slug' => $slug,
                'structure' => $processedData,
                'title' => $elementData['titles'] ?? null,
                'banner' => $elementData['banners'] ?? null,
                'image' => $elementData['images'] ?? null,
                'rich_text' => $elementData['rich_texts'] ?? null,
                'text_left_image_right' => $elementData['text_left_image_right'] ?? null,
                'custom_html' => $elementData['custom_htmls'] ?? null,
                'canvas_elements' => $elementData['canvas_elements'] ?? null,
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

    /**
     * Remove the specified page
     */
    public function destroy($id)
    {
        try {
            $page = Page::findOrFail($id);

            // Delete associated images from storage
            $this->deletePageImages($page);

            $page->delete();
            return redirect()->back()->with('success', 'Page deleted successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extract individual element data from form data for separate columns
     */
    private function extractElementData($formData)
    {
        $elementData = [
            'titles' => [],
            'banners' => [],
            'images' => [],
            'rich_texts' => [],
            'text_left_image_right' => [],
            'custom_htmls' => [], // This was missing the 's' at the end
            'canvas_elements' => []
        ];

        if (isset($formData['elements']) && is_array($formData['elements'])) {
            foreach ($formData['elements'] as $element) {
                $type = $element['type'] ?? null;
                $content = $element['content'] ?? [];
                $position = $element['position'] ?? 0;

                $elementInfo = [
                    'id' => $element['id'] ?? uniqid(),
                    'type' => $type,
                    'content' => $content,
                    'position' => $position,
                    'created_at' => now()->toISOString()
                ];

                // Map element types to database columns
                switch ($type) {
                    case 'heading':
                        $elementData['titles'][] = $elementInfo;
                        break;
                    case 'banner':
                        $elementData['banners'][] = $elementInfo;
                        break;
                    case 'image':
                        $elementData['images'][] = $elementInfo;
                        break;
                    case 'text':
                        $elementData['rich_texts'][] = $elementInfo;
                        break;
                    case 'columns':
                        $elementData['text_left_image_right'][] = $elementInfo;
                        break;
                    case 'custom_html': // Add this case for custom HTML
                        $elementData['custom_htmls'][] = $elementInfo;
                        break;
                        // Add more type mappings as needed
                }

                // Always add to canvas_elements for general storage
                $elementData['canvas_elements'][] = $elementInfo;
            }
        }

        return $elementData;
    }

    /**
     * Process images from base64 to file storage
     */
    private function processImages($data, $schoolId)
    {
        if (!isset($data['elements']) || !is_array($data['elements'])) {
            return $data;
        }

        $school = School::find($schoolId);
        if (!$school) {
            return $data;
        }

        $folderName = Str::slug($school->name);
        $processedImages = [];

        foreach ($data['elements'] as &$element) {
            if (in_array($element['type'], ['image', 'banner'])) {
                if (isset($element['content']['src']) && $this->isBase64Image($element['content']['src'])) {
                    $filePath = $this->saveBase64Image($element['content']['src'], $folderName);
                    if ($filePath) {
                        $processedImages[$element['content']['src']] = $filePath;
                        $element['content']['src'] = $filePath;
                    }
                }
            }

            // Process HTML content for base64 images
            foreach ($element['content'] as $key => &$value) {
                if (is_string($value) && $this->containsBase64Image($value)) {
                    $value = $this->processHtmlImages($value, $folderName, $processedImages);
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
     * Save base64 image to storage
     */
    private function saveBase64Image($base64Image, $folderName)
    {
        try {
            preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
            $imageType = $matches[1] ?? 'jpeg';
            $imageData = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

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
            \Log::error('Failed to save base64 image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process HTML content and replace base64 images
     */
    private function processHtmlImages($html, $folderName, &$processedImages = [])
    {
        preg_match_all('/src="(data:image\/[^"]+)"/', $html, $matches);

        if (empty($matches[1])) {
            return $html;
        }

        foreach ($matches[1] as $base64Image) {
            if ($this->isBase64Image($base64Image)) {
                if (isset($processedImages[$base64Image])) {
                    $html = str_replace($base64Image, $processedImages[$base64Image], $html);
                } else {
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
     * Check if HTML contains base64 images
     */
    private function containsBase64Image($html)
    {
        return is_string($html) && strpos($html, 'data:image/') !== false;
    }


    // ==========================

    /**
     * Prepare data for display by converting image paths to URLs
     */
    private function prepareForDisplay($structure)
    {
        if (!isset($structure['elements']) || !is_array($structure['elements'])) {
            return $structure;
        }

        foreach ($structure['elements'] as &$element) {
            if (isset($element['content'])) {
                // Convert image paths to URLs
                if (isset($element['content']['src']) && is_string($element['content']['src'])) {
                    $element['content']['src'] = $this->getCorrectImageUrl($element['content']['src']);
                }

                // Process HTML content for image paths
                foreach ($element['content'] as $key => &$value) {
                    if (is_string($value)) {
                        $value = $this->convertImagePathsToUrls($value);
                    }
                }
            }
        }

        return $structure;
    }

    /**
     * Get correct image URL with proper domain and path
     */
    private function getCorrectImageUrl($path)
    {
        // If it's already a full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // If it's a storage path (starts with school/), convert to proper URL
        if (strpos($path, 'school/') === 0) {
            // Use the asset helper with the correct path
            return asset('website/' . $path);
        }

        // For any other case, try to generate URL from storage
        try {
            return Storage::disk('website')->url($path);
        } catch (\Exception $e) {
            // Fallback: try to construct URL manually
            return url('storage/' . $path);
        }
    }

    /**
     * Convert storage paths to URLs in HTML content
     */
    private function convertImagePathsToUrls($content)
    {
        if (empty($content) || !is_string($content)) {
            return $content;
        }

        // Replace image paths in src attributes for school/ paths
        $content = preg_replace_callback(
            '/src=(["\'])(school\/[^\1]+?\.(jpg|jpeg|png|gif|webp))\1/i',
            function ($matches) {
                return 'src=' . $matches[1] . $this->getCorrectImageUrl($matches[2]) . $matches[1];
            },
            $content
        );

        return $content;
    }

    // ==========================






    /**
     * Get image URL from storage path
     */
    private function getImageUrl($path)
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        if (strpos($path, 'school/') === 0) {
            return Storage::disk('website')->url($path);
        }

        return $path;
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
     * Delete images associated with a page
     */
    private function deletePageImages($page)
    {
        try {
            // Delete images from structure
            if (isset($page->structure['elements']) && is_array($page->structure['elements'])) {
                foreach ($page->structure['elements'] as $element) {
                    if (
                        isset($element['content']['src']) &&
                        strpos($element['content']['src'], 'school/') === 0
                    ) {
                        Storage::disk('website')->delete($element['content']['src']);
                    }
                }
            }

            // Delete images from individual columns
            $imageColumns = ['banner', 'image', 'text_left_image_right'];
            foreach ($imageColumns as $column) {
                if ($page->$column && is_array($page->$column)) {
                    foreach ($page->$column as $item) {
                        if (
                            isset($item['content']['src']) &&
                            strpos($item['content']['src'], 'school/') === 0
                        ) {
                            Storage::disk('website')->delete($item['content']['src']);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete page images: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a page
     */
    public function duplicate($id)
    {
        try {
            $originalPage = Page::findOrFail($id);

            $newPage = $originalPage->replicate();
            $newPage->name = $originalPage->name . ' - Copy';
            $newPage->slug = $this->generateUniqueSlug($newPage->name);
            $newPage->uuid = Str::uuid();
            $newPage->created_at = now();
            $newPage->updated_at = now();

            $newPage->save();

            return response()->json([
                'success' => true,
                'message' => 'Page duplicated successfully!',
                'redirect_url' => route('pages.edit', $newPage->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pages by event (API endpoint)
     */
    public function getByEvent($eventId)
    {
        try {
            $pages = Page::where('event_id', $eventId)
                ->select('id', 'name', 'slug', 'created_at')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'pages' => $pages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pages: ' . $e->getMessage()
            ], 500);
        }
    }
}
