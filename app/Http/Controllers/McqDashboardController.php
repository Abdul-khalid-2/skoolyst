<?php

namespace App\Http\Controllers;

use App\Enums\ActiveStatus;
use App\Enums\ContentStatus;
use App\Models\Mcq;
use App\Models\MockTest;
use App\Models\Subject;
use App\Models\StudyMaterial;
use App\Models\TestType;
use App\Models\Topic;
use App\Models\BookCategory;
use App\Models\Book;
use Illuminate\Http\Request;

class McqDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.mcqs_system.mcqs.mcq-dashboard', [
            'stats' => $this->getDashboardStats()
        ]);
    }

    public function getStats()
    {
        return response()->json($this->getDashboardStats());
    }

    private function getDashboardStats()
    {
        return [
            'total_mcqs' => Mcq::count(),
            'published_mcqs' => Mcq::where('status', ContentStatus::Published)->count(),
            'total_tests' => MockTest::count(),
            'published_tests' => MockTest::where('status', ContentStatus::Published)->count(),
            'total_subjects' => Subject::count(),
            'active_subjects' => Subject::where('status', ActiveStatus::Active)->count(),
            'total_topics' => Topic::count(),
            'study_materials' => StudyMaterial::count(),
            'free_materials' => StudyMaterial::where('is_free', true)->count(),
            'today_mcqs' => Mcq::whereDate('created_at', today())->count(),
            'premium_mcqs' => Mcq::where('is_premium', true)->count(),
            'unverified_mcqs' => Mcq::where('is_verified', false)->count(),
        ];
    }
}
