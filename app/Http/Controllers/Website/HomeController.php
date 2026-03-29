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
        $filters = [
            'search' => $request->search,
            'location' => $request->location,
            'type' => $request->type,
            'curriculum' => $request->curriculum,
        ];

        $response = $this->homeService->searchSchools($filters);
        
        return response()->json($response);
    }

    public function howItWorks()
    {
        $response = $this->homeService->getHowItWorksPage();
        return view('website.how_it_works', $response['data']);
    }
}
