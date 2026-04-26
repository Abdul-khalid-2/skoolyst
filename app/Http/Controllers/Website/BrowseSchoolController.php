<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class BrowseSchoolController extends Controller
{
    protected $schoolService;

    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'location' => $request->get('location'),
            'type' => $request->get('type'),
            'curriculum' => $request->get('curriculum'),
        ];

        $schools = $this->schoolService->searchSchools($filters, 12);
        $filterData = $this->schoolService->getFilterData();

        return view('website.browse_schools', array_merge(
            $filterData,
            [
                'schools' => $schools,
                'search' => $filters['search'],
                'location' => $filters['location'],
                'type' => $filters['type'],
                'curriculum' => $filters['curriculum'],
                'pageSetsOwnCanonical' => true,
            ]
        ));
    }

    public function search(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'location' => $request->location,
            'type' => $request->type,
            'curriculum' => $request->curriculum,
        ];

        $perPage = (int) $request->input('per_page', 20);
        $paginator = $this->schoolService->getAllSchools($filters, $perPage);

        return response()->json([
            'schools' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ]);
    }

    public function show($uuid)
    {
        $school = $this->schoolService->getSchoolByUuid($uuid);
        return view('website.school_profile', compact('school'));
    }
}