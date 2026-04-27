<?php

namespace App\Services;

use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Models\School;
use App\Support\CacheKeys;
use App\Models\SchoolImage;
use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminSchoolService
{
    public function createSchool(StoreSchoolRequest $request, ImageWebpService $imageWebp): void
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $regularFees = null;
            $discountedFees = null;
            $admissionFees = $validated['admission_fees'] ?? null;
            $classWiseFees = null;

            if ($validated['fee_structure_type'] === 'fixed') {
                $regularFees = $validated['regular_fees'] ?? null;
                $discountedFees = $validated['discounted_fees'] ?? null;
            } else {
                $transformedFees = [];
                if ($validated['fee_structure_type'] === 'class_wise' && isset($validated['class_wise_fees'])) {
                    foreach ($validated['class_wise_fees'] as $feeEntry) {
                        $transformedFees[$feeEntry['range']] = $feeEntry['amount'];
                    }
                }
                $classWiseFees = json_encode($transformedFees);
            }

            $school = School::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'contact_number' => $validated['contact_number'] ?? null,
                'email' => $validated['email'],
                'website' => $validated['website'] ?? null,
                'facilities' => $validated['facilities'] ?? null,
                'school_type' => $validated['school_type'],
                'fee_structure_type' => $validated['fee_structure_type'],
                'regular_fees' => $regularFees,
                'discounted_fees' => $discountedFees,
                'admission_fees' => $admissionFees,
                'class_wise_fees' => $classWiseFees,
                'status' => $validated['status'],
                'visibility' => $validated['visibility'],
                'publish_date' => $validated['publish_date'] ?? null,
                'banner_title' => $validated['banner_title'] ?? null,
                'banner_tagline' => $validated['banner_tagline'] ?? null,
            ]);

            $profileData = [
                'school_id' => $school->id,
                'established_year' => $validated['established_year'] ?? null,
                'student_strength' => $validated['student_strength'] ?? null,
                'faculty_count' => $validated['faculty_count'] ?? null,
                'campus_size' => $validated['campus_size'] ?? null,
                'school_motto' => $validated['school_motto'] ?? null,
                'mission' => $validated['mission'] ?? null,
                'vision' => $validated['vision'] ?? null,
            ];

            $quickFacts = [];
            if ($request->has('quick_fact_keys') && $request->has('quick_fact_values')) {
                $keys = $request->quick_fact_keys;
                $values = $request->quick_fact_values;
                for ($i = 0, $c = count($keys); $i < $c; $i++) {
                    if (! empty($keys[$i]) && ! empty($values[$i])) {
                        $quickFacts[$keys[$i]] = $values[$i];
                    }
                }
            }
            $profileData['quick_facts'] = ! empty($quickFacts) ? json_encode($quickFacts) : null;

            $socialMedia = [];
            $socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
            foreach ($socialPlatforms as $platform) {
                $urlField = $platform . '_url';
                if (! empty($validated[$urlField] ?? '')) {
                    $socialMedia[$platform] = $validated[$urlField];
                }
            }
            $profileData['social_media'] = ! empty($socialMedia) ? json_encode($socialMedia) : null;

            $schoolProfile = SchoolProfile::create($profileData);

            $folderName = Str::slug($school->name, '-');

            if ($request->hasFile('logo')) {
                $logoPath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/logo", $request->file('logo'));
                $schoolProfile->update(['logo' => $logoPath]);
            }

            if ($request->hasFile('banner_image')) {
                $bannerPath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/banner", $request->file('banner_image'));
                $school->update(['banner_image' => $bannerPath]);
            }

            if ($request->hasFile('school_images')) {
                foreach ($request->file('school_images') as $index => $imageFile) {
                    if ($imageFile) {
                        $imagePath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/gallery", $imageFile);
                        SchoolImage::create([
                            'school_id' => $school->id,
                            'image_path' => $imagePath,
                            'title' => $request->image_titles[$index] ?? null,
                        ]);
                    }
                }
            }

            if ($request->has('features')) {
                $school->features()->attach($request->features);
            }
            if ($request->has('curriculum_ids')) {
                $school->curriculums()->attach($request->curriculum_ids);
            }

            $user = new User;
            $user->name = $validated['admin-name'] ?? $validated['name'] . ' Admin';
            $user->email = $validated['admin-email'] ?? $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->school_id = $school->id;
            $user->save();
            $user->assignRole('school-admin');

            $school->refresh();
            SchoolTranslationService::syncFromRequest($school, $request->input('translations', []));

            DB::commit();
            $this->clearSchoolListingCaches($school);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateSchool(School $school, UpdateSchoolRequest $request, ImageWebpService $imageWebp): void
    {
        $validated = $request->validated();
        $imagesToRemove = $request->input('remove_images', []);

        DB::beginTransaction();

        try {
            $regularFees = null;
            $discountedFees = null;
            $admissionFees = $validated['admission_fees'] ?? null;
            $classWiseFees = null;

            if ($validated['fee_structure_type'] === 'fixed') {
                $regularFees = $validated['regular_fees'] ?? null;
                $discountedFees = $validated['discounted_fees'] ?? null;
            } else {
                $transformedFees = [];
                if ($validated['fee_structure_type'] === 'class_wise' && isset($validated['class_wise_fees'])) {
                    foreach ($validated['class_wise_fees'] as $feeEntry) {
                        $transformedFees[$feeEntry['range']] = $feeEntry['amount'];
                    }
                }
                $classWiseFees = json_encode($transformedFees);
            }

            $school->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'contact_number' => $validated['contact_number'] ?? null,
                'email' => $validated['email'] ?? null,
                'website' => $validated['website'] ?? null,
                'facilities' => $validated['facilities'] ?? null,
                'school_type' => $validated['school_type'],
                'fee_structure_type' => $validated['fee_structure_type'],
                'regular_fees' => $regularFees,
                'discounted_fees' => $discountedFees,
                'admission_fees' => $admissionFees,
                'class_wise_fees' => $classWiseFees,
                'status' => $validated['status'],
                'visibility' => $validated['visibility'],
                'publish_date' => $validated['publish_date'] ?? null,
                'banner_title' => $validated['banner_title'] ?? null,
                'banner_tagline' => $validated['banner_tagline'] ?? null,
            ]);

            $profileData = [
                'established_year' => $validated['established_year'] ?? null,
                'student_strength' => $validated['student_strength'] ?? null,
                'faculty_count' => $validated['faculty_count'] ?? null,
                'campus_size' => $validated['campus_size'] ?? null,
                'school_motto' => $validated['school_motto'] ?? null,
                'mission' => $validated['mission'] ?? null,
                'vision' => $validated['vision'] ?? null,
            ];

            $quickFacts = [];
            if ($request->has('quick_fact_keys') && $request->has('quick_fact_values')) {
                $keys = $request->quick_fact_keys;
                $values = $request->quick_fact_values;
                for ($i = 0, $c = count($keys); $i < $c; $i++) {
                    if (! empty($keys[$i]) && ! empty($values[$i])) {
                        $quickFacts[$keys[$i]] = $values[$i];
                    }
                }
            }
            $profileData['quick_facts'] = ! empty($quickFacts) ? json_encode($quickFacts) : null;

            $socialMedia = [];
            $socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
            foreach ($socialPlatforms as $platform) {
                $urlField = $platform . '_url';
                if (! empty($validated[$urlField] ?? '')) {
                    $socialMedia[$platform] = $validated[$urlField];
                }
            }
            $profileData['social_media'] = ! empty($socialMedia) ? json_encode($socialMedia) : null;

            if ($school->profile) {
                $school->profile->update($profileData);
                $schoolProfile = $school->profile;
            } else {
                $profileData['school_id'] = $school->id;
                $schoolProfile = SchoolProfile::create($profileData);
            }

            if ($request->has('features')) {
                $school->features()->sync($request->features);
            } else {
                $school->features()->detach();
            }
            if ($request->has('curriculum_ids')) {
                $school->curriculums()->sync($request->curriculum_ids);
            } else {
                $school->curriculums()->detach();
            }

            $folderName = Str::slug($school->name, '-');
            $shouldRemoveLogo = $request->filled('remove_logo');
            $hasNewLogo = $request->hasFile('logo');
            if ($shouldRemoveLogo && $schoolProfile->logo) {
                Storage::disk('website')->delete($schoolProfile->logo);
                $schoolProfile->update(['logo' => null]);
            }
            if ($hasNewLogo) {
                if ($schoolProfile->logo) {
                    Storage::disk('website')->delete($schoolProfile->logo);
                }
                $logoPath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/logo", $request->file('logo'));
                $schoolProfile->update(['logo' => $logoPath]);
            }

            $shouldRemoveBanner = $request->filled('remove_banner');
            $hasNewBanner = $request->hasFile('banner_image');
            if ($shouldRemoveBanner && $school->banner_image) {
                Storage::disk('website')->delete($school->banner_image);
                $school->update(['banner_image' => null]);
            }
            if ($hasNewBanner) {
                if ($school->banner_image) {
                    Storage::disk('website')->delete($school->banner_image);
                }
                $bannerPath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/banner", $request->file('banner_image'));
                $school->update(['banner_image' => $bannerPath]);
            }

            if (! empty($imagesToRemove)) {
                $imagesToDelete = SchoolImage::where('school_id', $school->id)
                    ->whereIn('id', $imagesToRemove)
                    ->get();
                foreach ($imagesToDelete as $image) {
                    Storage::disk('website')->delete($image->image_path);
                    $image->delete();
                }
            }

            if ($request->hasFile('school_images')) {
                foreach ($request->file('school_images') as $index => $imageFile) {
                    $imagePath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/gallery", $imageFile);
                    SchoolImage::create([
                        'school_id' => $school->id,
                        'image_path' => $imagePath,
                        'title' => $request->input("image_titles.{$index}"),
                    ]);
                }
            }

            $user = $school->user;
            if ($user) {
                $user->name = $validated['admin-name'];
                $user->email = $validated['admin-email'];
                if (! empty($validated['password'])) {
                    $user->password = bcrypt($validated['password']);
                }
                $user->save();
            } else {
                $user = User::create([
                    'name' => $validated['admin-name'],
                    'email' => $validated['admin-email'],
                    'password' => bcrypt($validated['password'] ?? 'default123'),
                    'school_id' => $school->id,
                ]);
                $user->assignRole('school-admin');
            }
            $school->refresh();
            SchoolTranslationService::syncFromRequest($school, $request->input('translations', []));

            DB::commit();
            $this->clearSchoolListingCaches($school);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function clearSchoolListingCaches(School $school): void
    {
        foreach (CacheKeys::homeLocales() as $loc) {
            Cache::forget(CacheKeys::homePageData($loc));
        }
        Cache::forget(CacheKeys::schoolCitiesList());
        CacheKeys::forgetDirectoryFirstPageCaches();
        Cache::forget(CacheKeys::schoolPublicShowByUuid($school->uuid));
    }
}
