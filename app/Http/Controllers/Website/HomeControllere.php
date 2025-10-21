<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeControllere extends Controller
{
    public function home()
    {
        return view('website.home');
    }
    public function howItWorks()
    {
        return view('website.how_it_works');
    }
}
