<?php

namespace App\Http\Requests;

use App\Enums\ContactInquiryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactInquiryStatusRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ContactInquiryStatus::class)],
            'admin_notes' => 'nullable|string|max:1000',
        ];
    }
}
