<?php

namespace App\Http\Controllers;

use App\Models\SchoolImageGallery;
use App\Services\ImageWebpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchoolImageGalleryController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = $request->get('school_id');

        $images = SchoolImageGallery::where('school_id', $schoolId)
            ->where('status', 'active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }

    public function store(Request $request, ImageWebpService $imageWebp)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'image_name' => 'required|string|max:255',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {

            if (auth()->user()->hasRole('school-admin')) {
                $schoolId = auth()->user()->school_id;
            } else {
                $schoolId = $request->school_id;
            }
            // $schoolId = $request->school_id;
            $imageFile = $request->file('image_file');

            // Get school to create folder name
            $school = \App\Models\School::findOrFail($schoolId);
            $folderName = Str::slug($school->name, '-');

            $imagePath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/gallery", $imageFile);
            $uniqueName = basename($imagePath);

            $image = SchoolImageGallery::create([
                'school_id' => $schoolId,
                'image_path' => $imagePath,
                'image_name' => $request->image_name,
                'image_unique_name' => $uniqueName,
                'status' => 'active'
            ]);



            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image' => $image
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show(SchoolImageGallery $schoolImageGallery)
    {
        return response()->json([
            'success' => true,
            'image' => $schoolImageGallery
        ]);
    }

    public function update(Request $request, SchoolImageGallery $schoolImageGallery)
    {
        $request->validate([
            'image_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        try {


            $schoolImageGallery->update([
                'image_name' => $request->image_name,
                'status' => $request->status
            ]);



            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'image' => $schoolImageGallery
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(SchoolImageGallery $schoolImageGallery)
    {
        try {


            $rel = (string) $schoolImageGallery->image_path;
            if (str_starts_with($rel, 'website/')) {
                $rel = substr($rel, strlen('website/'));
            }
            if ($rel !== '' && Storage::disk('website')->exists($rel)) {
                Storage::disk('website')->delete($rel);
            } elseif (file_exists(public_path($schoolImageGallery->image_path))) {
                unlink(public_path($schoolImageGallery->image_path));
            }

            // Delete database record
            $schoolImageGallery->delete();



            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }
}
