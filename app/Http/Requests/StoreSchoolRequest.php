<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_title' => 'nullable|string',
            'banner_tagline' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:schools,email',
            'admin-name' => 'required|string|max:255',
            'admin-email' => 'required|email|max:100|unique:users,email',
            'website' => 'nullable|url|max:255',
            'facilities' => 'nullable|string',
            'school_type' => 'required|in:Co-Ed,Boys,Girls',
            'fee_structure_type' => 'required|in:fixed,class_wise',
            'regular_fees' => 'nullable|numeric|min:0',
            'discounted_fees' => 'nullable|numeric|min:0',
            'admission_fees' => 'nullable|numeric|min:0',
            'class_wise_fees' => 'required_if:fee_structure_type,class_wise|array|min:1|max:5',
            'class_wise_fees.*.range' => 'required|string|max:25',
            'class_wise_fees.*.amount' => 'required|string|max:15',
            'status' => 'required|in:active,inactive',
            'visibility' => 'required|in:public,private',
            'publish_date' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'curriculum_ids' => 'required|array|min:1',
            'curriculum_ids.*' => 'exists:curriculums,id',
            'established_year' => 'nullable|string|max:20',
            'student_strength' => 'nullable|integer|min:0',
            'faculty_count' => 'nullable|integer|min:0',
            'campus_size' => 'nullable|string|max:100',
            'school_motto' => 'nullable|string|max:255',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'quick_fact_keys' => 'nullable|array',
            'quick_fact_values' => 'nullable|array',
            'school_images' => 'nullable|array',
            'school_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_titles' => 'nullable|array',
            'image_titles.*' => 'nullable|string|max:255',
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
}
