<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function home()
    {
        $data = $this->homeService->getHomePage();
        
        return view('website.home', [
            'schools' => $data['schools'],
            'curriculums' => $data['curriculums'],
            'cities' => $data['cities'],
            'schoolTypes' => $data['schoolTypes'],
            'testimonials' => $data['testimonials'],
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
            'location' => ['nullable', 'string', 'max:120'],
            'type' => ['nullable', 'string', 'max:50'],
            'curriculum' => ['nullable', 'string', 'max:50'],
        ]);

        $filters = [
            'search' => $validated['search'] ?? null,
            'location' => $validated['location'] ?? null,
            'type' => $validated['type'] ?? null,
            'curriculum' => $validated['curriculum'] ?? null,
        ];

        return response()->json($this->homeService->searchSchools($filters));
    }

    /**
     * Typeahead / live search for homepage (max 6 results, same filters as /search).
     */
    public function searchSuggest(Request $request)
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'location' => ['nullable', 'string', 'max:120'],
            'type' => ['nullable', 'string', 'max:50'],
            'curriculum' => ['nullable', 'string', 'max:50'],
        ]);

        $filters = [
            'location' => $validated['location'] ?? null,
            'type' => $validated['type'] ?? null,
            'curriculum' => $validated['curriculum'] ?? null,
        ];

        return response()->json(
            $this->homeService->searchSchoolsSuggest($filters, $validated['q'], 6)
        );
    }

    public function howItWorks()
    {
        $response = $this->homeService->getHowItWorksPage();
        return view('website.how_it_works', $response['data']);
    }
}
