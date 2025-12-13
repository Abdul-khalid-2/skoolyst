<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Branch;
use App\Models\Feature;
use App\Models\BranchImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    /**
     * Display a listing of the branches for a specific school.
     */
    public function index(School $school)
    {
        try {
            $branches = $school->branches()->withCount(['images', 'events', 'reviews'])
                ->latest()->paginate(10);
            return view('dashboard.branches.index', compact('school', 'branches'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load branches. Please try again.');
        }
    }

    /**
     * Show the form for creating a new branch for a specific school.
     */
    public function create(School $school)
    {
        $features = Feature::all();
        return view('dashboard.branches.create', compact('school', 'features'));
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request, School $school)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'branch_head_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'school_type' => 'nullable|in:Co-Ed,Boys,Girls',
                'fee_structure' => 'nullable|string',
                'curriculums' => 'nullable|string',
                'classes' => 'nullable|string',
                'features' => 'nullable|array',
                'features.*' => 'exists:features,id',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_main_branch' => 'boolean',
            ]);

            // Prepare features as JSON
            if ($request->has('features')) {
                $validated['features'] = json_encode($request->features);
            }

            // If setting this as main branch, remove main branch status from others
            if ($request->has('is_main_branch') && $request->is_main_branch) {
                $school->branches()->update(['is_main_branch' => false]);
            }

            $branch = $school->branches()->create($validated);

            return redirect()->route('schools.branches.index', $school)
                ->with('success', 'Branch created successfully. You can now add images to the branch.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create branch. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified branch.
     */
    public function show(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $branch->load(['images', 'events', 'reviews']);
        return view('dashboard.branches.show', compact('school', 'branch'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $features = Feature::all();
        $branch->load('images');
        return view('dashboard.branches.edit', compact('school', 'branch', 'features'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'branch_head_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'school_type' => 'nullable|in:Co-Ed,Boys,Girls',
                'fee_structure' => 'nullable|string',
                'curriculums' => 'nullable|string',
                'classes' => 'nullable|string',
                'features' => 'nullable|array',
                'features.*' => 'exists:features,id',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_main_branch' => 'boolean',
                'status' => 'required|in:active,inactive',
            ]);

            // Prepare features as JSON
            if ($request->has('features')) {
                $validated['features'] = json_encode($request->features);
            } else {
                $validated['features'] = null;
            }

            // If setting this as main branch, remove main branch status from others
            if ($request->has('is_main_branch') && $request->is_main_branch) {
                $school->branches()->where('id', '!=', $branch->id)->update(['is_main_branch' => false]);
            }

            $branch->update($validated);

            return redirect()->route('schools.branches.index', $school)
                ->with('success', 'Branch updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update branch. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        try {
            // Delete all associated images
            $branch->images()->each(function ($image) {
                if (Storage::exists($image->image_path)) {
                    Storage::delete($image->image_path);
                }
                $image->delete();
            });

            // Prevent deletion of the only branch if it's the main branch
            if ($branch->is_main_branch && $school->branches()->count() === 1) {
                return redirect()->back()
                    ->with('error', 'Cannot delete the only branch. Please create another branch first.');
            }

            $branch->delete();

            // If we deleted the main branch, set another branch as main
            if ($branch->is_main_branch) {
                $newMainBranch = $school->branches()->first();
                if ($newMainBranch) {
                    $newMainBranch->update(['is_main_branch' => true]);
                }
            }

            return redirect()->route('schools.branches.index', $school)
                ->with('success', 'Branch deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete branch. Please try again.');
        }
    }

    /**=======================================================================
     * Display image management page
     */
    public function imagesIndex(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $images = $branch->images()->orderBy('sort_order')->get();
        $imageTypes = BranchImage::getTypeOptions();

        return view('dashboard.branches.images.index', compact('school', 'branch', 'images', 'imageTypes'));
    }

    /**
     * Upload multiple images
     */
    public function storeImages(Request $request, School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $request->validate([
            'images' => 'required|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            'type' => 'nullable|in:gallery,banner,infrastructure,events,classroom',
        ]);

        $uploadedImages = [];

        try {
            $imageType = $request->type ?? 'gallery';

            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $uniqueName = Str::uuid() . '.' . $extension;

                $imagePath = Storage::disk('website')
                    ->putFile("school/{$school->id}/branches/{$branch->id}/{$imageType}", $image);

                // Get next sort order
                $maxSortOrder = $branch->images()->max('sort_order') ?? 0;

                // Create image record
                $branchImage = BranchImage::create([
                    'uuid' => Str::uuid(),
                    'branch_id' => $branch->id,
                    'image_path' => $imagePath,
                    'image_name' => pathinfo($originalName, PATHINFO_FILENAME),
                    'image_unique_name' => $uniqueName,
                    'title' => pathinfo($originalName, PATHINFO_FILENAME),
                    'type' => $imageType,
                    'sort_order' => $maxSortOrder + 1,
                    'status' => 'active',
                ]);

                $uploadedImages[] = [
                    'id' => $branchImage->id,
                    'title' => $branchImage->title,
                    'type' => $branchImage->type,
                    'is_featured' => (bool)$branchImage->is_featured,
                    'is_main_banner' => (bool)$branchImage->is_main_banner,
                    'image_path' => $branchImage->image_path,
                    'image_url' => Storage::disk('website')->url($branchImage->image_path),
                    'created_at' => $branchImage->created_at->toDateTimeString()
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully',
                'images' => $uploadedImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update image metadata
     */
    public function updateImage(Request $request, School $school, Branch $branch, BranchImage $image)
    {
        // Verify the image belongs to the branch
        if ($image->branch_id !== $branch->id || $branch->school_id !== $school->id) {
            abort(404);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'type' => 'required|in:gallery,banner,infrastructure,events,classroom',
            'is_featured' => 'boolean',
            'is_main_banner' => 'boolean',
        ]);

        try {
            $data = $request->only(['title', 'caption', 'type', 'is_featured', 'is_main_banner']);

            // If setting as featured, remove featured status from other images
            if ($request->has('is_featured') && $request->is_featured) {
                $branch->images()->where('id', '!=', $image->id)->update(['is_featured' => false]);
            }

            // If setting as main banner, remove main banner status from other images
            if ($request->has('is_main_banner') && $request->is_main_banner) {
                $branch->images()->where('id', '!=', $image->id)->update(['is_main_banner' => false]);
            }

            $image->update($data);
            $image->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'image' => [
                    'id' => $image->id,
                    'title' => $image->title,
                    'caption' => $image->caption,
                    'type' => $image->type,
                    'is_featured' => (bool)$image->is_featured,
                    'is_main_banner' => (bool)$image->is_main_banner,
                    'image_path' => $image->image_path,
                    'image_url' => Storage::disk('website')->url($image->image_path),
                    'updated_at' => $image->updated_at->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete image
     */
    public function deleteImage(School $school, Branch $branch, BranchImage $image)
    {
        // Verify the image belongs to the branch
        if ($image->branch_id !== $branch->id || $branch->school_id !== $school->id) {
            abort(404);
        }

        try {
            // ✅ Delete physical file from website disk
            if (Storage::disk('website')->exists($image->image_path)) {
                Storage::disk('website')->delete($image->image_path);
            }

            // Delete record
            $image->delete();

            // Reorder remaining images
            $this->reorderImagesAfterDeletion($branch);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    /**
     * Reorder images via drag & drop
     */
    public function reorderImages(Request $request, School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:branch_images,id',
        ]);

        try {
            foreach ($request->order as $index => $imageId) {
                BranchImage::where('id', $imageId)
                    ->where('branch_id', $branch->id)
                    ->update(['sort_order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Images reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder images'
            ], 500);
        }
    }

    /**
     * Helper method to reorder images after deletion
     */
    private function reorderImagesAfterDeletion(Branch $branch)
    {
        $images = $branch->images()->orderBy('sort_order')->get();

        foreach ($images as $index => $image) {
            $image->update(['sort_order' => $index + 1]);
        }
    }

    /**
     * Get image statistics
     */
    public function getImageStats(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $stats = [
            'total' => $branch->images()->count(),
            'gallery' => $branch->images()->where('type', 'gallery')->count(),
            'banner' => $branch->images()->where('type', 'banner')->count(),
            'infrastructure' => $branch->images()->where('type', 'infrastructure')->count(),
            'events' => $branch->images()->where('type', 'events')->count(),
            'classroom' => $branch->images()->where('type', 'classroom')->count(),
            'featured' => $branch->images()->where('is_featured', true)->count(),
            'main_banner' => $branch->images()->where('is_main_banner', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
