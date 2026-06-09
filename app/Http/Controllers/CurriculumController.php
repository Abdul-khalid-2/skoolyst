<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Support\CacheKeys;

class CurriculumController extends Controller
{
    public function quickStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:curriculums,name',
            'description' => 'nullable|string|max:500',
        ]);

        $code = Str::slug($validated['name'], '_');
        if ($code === '') {
            $code = 'curriculum_' . time();
        }

        $baseCode = $code;
        $suffix = 1;
        while (Curriculum::where('code', $code)->exists()) {
            $code = $baseCode . '_' . $suffix;
            $suffix++;
        }

        $curriculum = Curriculum::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'] ?? null,
        ]);

        Cache::forget(CacheKeys::curriculumList());

        return response()->json([
            'success' => true,
            'curriculum' => $curriculum,
        ], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
