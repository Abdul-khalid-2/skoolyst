<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeApiController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function home()
    {
        return response()->json([
            'home' => $this->homeService->getHomePage(),
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

        return response()->json($this->homeService->searchSchools($filters));
    }

    public function howItWorks()
    {
        return response()->json([
            'how_it_works' => $this->homeService->getHowItWorksPage(),
        ]);
    }

    public function getCities()
    {
        return response()->json([
            'cities' => $this->homeService->getCities(),
        ]);
    }

    public function getCurriculums()
    {
        return response()->json([
            'curriculums' => $this->homeService->getCurriculums(),
        ]);
    }

    public function getSchoolTypes()
    {
        return response()->json([
            'school_types' => $this->homeService->getSchoolTypes(),
        ]);
    }
}