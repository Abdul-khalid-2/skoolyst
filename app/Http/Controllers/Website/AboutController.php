<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return view('website.about');
    }

    public function insights()
    {
        return view('website.insights.index');
    }

    public function digitalTransformation()
    {
        return view('website.insights.digital-transformation');
    }

    public function schoolCommunity()
    {
        return view('website.insights.school-community');
    }

    public function schoolMarketing()
    {
        return view('website.insights.school-marketing');
    }
}
