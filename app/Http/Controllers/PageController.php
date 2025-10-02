<?php
// app/Http/Controllers/PageController.php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Event; // If you need to load events
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
        $events = Event::where('status', 'active')->get(['id', 'event_name']);
        return view('dashboard.events.advertisement_template', compact('events'));
    }

    /**
     * Store the form data securely
     */
    public function store(Request $request)
    {
        // Validate the request
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
            // Decode and sanitize the JSON data
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
                'redirect_url' => route('pages.show', $page->slug) // Optional: add a show route
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
            // Use multiple sanitization methods for security
            return $this->deepSanitize($data);
        } else {
            return $data;
        }
    }

    /**
     * Deep sanitization for strings
     */
    private function deepSanitize($string)
    {
        // Remove any NULL characters
        $string = str_replace("\0", '', $string);

        // Convert special characters to HTML entities
        $string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove script tags and their content
        $string = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $string);

        // Remove other dangerous tags
        $dangerousTags = ['iframe', 'object', 'embed', 'form', 'input', 'button', 'select', 'textarea'];
        foreach ($dangerousTags as $tag) {
            $string = preg_replace('/<' . $tag . '\b[^>]*>(.*?)<\/' . $tag . '>/is', '', $string);
            $string = preg_replace('/<' . $tag . '\b[^>]*\/?>/i', '', $string);
        }

        // Remove dangerous attributes
        $dangerousAttributes = ['onclick', 'onload', 'onerror', 'onmouseover', 'onmouseout', 'onkeydown', 'onkeypress', 'style', 'href'];
        foreach ($dangerousAttributes as $attr) {
            $string = preg_replace('/\s+' . $attr . '\s*=\s*["\'][^"\']*["\']/i', '', $string);
            $string = preg_replace('/\s+' . $attr . '\s*=\s*[^"\'][^>]*/i', '', $string);
        }

        // Trim and return
        return trim($string);
    }

    /**
     * Display a specific page (optional - for viewing saved pages)
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('dashboard.events.advertisement_show', compact('page'));
    }
}
