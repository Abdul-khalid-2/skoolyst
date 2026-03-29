<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\AboutService;

class AboutController extends Controller
{
    protected $aboutService;

    public function __construct(AboutService $aboutService)
    {
        $this->aboutService = $aboutService;
    }

    public function index()
    {
        $response = $this->aboutService->getAboutPage();
        return view('website.about', $response['data']);
    }

    public function insights()
    {
        $response = $this->aboutService->getInsightsPage();
        return view('website.insights.index', $response['data']);
    }

    public function digitalTransformation()
    {
        $response = $this->aboutService->getDigitalTransformationPage();
        return view('website.insights.digital-transformation', $response['data']);
    }

    public function schoolCommunity()
    {
        $response = $this->aboutService->getSchoolCommunityPage();
        return view('website.insights.school-community', $response['data']);
    }

    public function schoolMarketing()
    {
        $response = $this->aboutService->getSchoolMarketingPage();
        return view('website.insights.school-marketing', $response['data']);
    }
}