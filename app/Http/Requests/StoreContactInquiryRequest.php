<?php

namespace App\Http\Requests;

use App\Enums\ContactInquirySubject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactInquiryRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => ['required', Rule::enum(ContactInquirySubject::class)],
            'custom_subject' => 'required_if:subject,'.ContactInquirySubject::Other->value.'|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Please login to submit an inquiry.',
                'login_required' => true,
            ], 401)
        );
    }
}
