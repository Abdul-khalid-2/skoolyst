<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementPageController extends Controller
{

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
        return view('website.advertisements_list', compact('pages', 'id', 'school_uuid', 'event'));
    }

    public function show($slug, string $page_uuid)
    {
        dd(123);
        $page = Page::where('slug', $slug)->where('uuid', $page_uuid)->firstOrFail();

        // Prepare data for display - convert image paths to URLs
        $page->structure = $this->prepareForDisplay($page->structure);

        return view('website.advertisement_page', compact('page'));
    }
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
}
