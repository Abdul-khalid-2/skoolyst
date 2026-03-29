<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AboutService;

class AboutApiController extends Controller
{
    protected $aboutService;

    public function __construct(AboutService $aboutService)
    {
        $this->aboutService = $aboutService;
    }


    public function index()
    {
        return response()->json([
            'website.about' => $this->aboutService->getAboutPage(),
        ]);
    }

    public function insights()
    {
        return response()->json([
            'api.insights.index' => $this->aboutService->getInsightsPage(),
        ]);
    }

    public function digitalTransformation()
    {
        return response()->json([
            'api.insights.digital-transformation' => $this->aboutService->getDigitalTransformationPage(),
        ]);
    }

    public function schoolCommunity()
    {
        return response()->json([
            'api.insights.school-community' => $this->aboutService->getSchoolCommunityPage(),
        ]);
    }

    public function schoolMarketing()
    {
        return response()->json([
            'api.insights.school-marketing' => $this->aboutService->getSchoolMarketingPage(),
        ]);
    }
}