<?php

namespace App\Http\Requests;

use App\Models\School;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolRequest extends FormRequest
{
    private ?School $resolvedSchool = null;

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $school = $this->resolveSchool();

        $adminEmailRule = ['required', 'email', 'max:100'];
        if ($this->input('admin-email') !== optional($school->user)->email) {
            $adminEmailRule[] = Rule::unique('users', 'email');
        }

        $schoolEmailRule = ['nullable', 'email', 'max:100'];
        if ($this->input('email') !== $school->email) {
            $schoolEmailRule[] = Rule::unique('schools', 'email')->ignore($school->id);
        }

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'email' => $schoolEmailRule,
            'admin-name' => 'required|string|max:255',
            'admin-email' => $adminEmailRule,
            'website' => 'nullable|url|max:255',
            'facilities' => 'nullable|string',
            'school_type' => 'required|in:Co-Ed,Boys,Girls,Separate',
            'fee_structure_type' => 'required|in:fixed,class_wise',
            'established_year' => 'nullable|string|max:4',
            'student_strength' => 'nullable|integer|min:0',
            'faculty_count' => 'nullable|integer|min:0',
            'campus_size' => 'nullable|string|max:100',
            'school_motto' => 'nullable|string|max:255',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'regular_fees' => 'nullable|numeric|min:0',
            'discounted_fees' => 'nullable|numeric|min:0',
            'admission_fees' => 'nullable|numeric|min:0',
            'class_wise_fees' => 'required_if:fee_structure_type,class_wise|array|min:1|max:5',
            'class_wise_fees.*.range' => 'required|string|max:25',
            'class_wise_fees.*.amount' => 'required|string|max:15',
            'status' => 'required|in:active,inactive',
            'visibility' => 'required|in:public,private',
            'publish_date' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_title' => 'nullable|string|max:255',
            'banner_tagline' => 'nullable|string|max:255',
            'school_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_titles' => 'nullable|array',
            'image_titles.*' => 'nullable|string|max:255',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'exists:school_images,id,school_id,' . $school->id,
            'remove_logo' => 'nullable|boolean',
            'remove_banner' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'curriculum_ids' => 'required|array|min:1',
            'curriculum_ids.*' => 'exists:curriculums,id',
            'quick_fact_keys' => 'nullable|array',
            'quick_fact_values' => 'nullable|array',
            'translations' => 'nullable|array',
            'translations.ur' => 'nullable|array',
            'translations.ur.name' => 'nullable|string|max:255',
            'translations.ur.description' => 'nullable|string',
            'translations.ur.facilities' => 'nullable|string',
            'translations.ur.banner_title' => 'nullable|string|max:255',
            'translations.ur.banner_tagline' => 'nullable|string|max:255',
            'translations.ur.school_motto' => 'nullable|string|max:255',
            'translations.ur.mission' => 'nullable|string',
            'translations.ur.vision' => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $school = $this->resolveSchool();
            $school->load('images');
            $currentImageCount = $school->images->count();
            $remove = $this->input('remove_images', []);
            $imagesToKeepCount = $currentImageCount - count($remove);
            $newImageCount = $this->hasFile('school_images') ? count($this->file('school_images')) : 0;
            if (($imagesToKeepCount + $newImageCount) > 10) {
                $v->errors()->add('school_images', 'Total number of school images cannot exceed 10.');
            }
        });
    }

    public function resolveSchool(): School
    {
        if ($this->resolvedSchool !== null) {
            return $this->resolvedSchool;
        }
        $p = $this->route('school') ?? $this->route('id');
        if ($p instanceof School) {
            $this->resolvedSchool = $p->loadMissing(['user', 'images']);
        } else {
            $this->resolvedSchool = School::with(['user', 'images'])->findOrFail($p);
        }

        return $this->resolvedSchool;
    }
}
