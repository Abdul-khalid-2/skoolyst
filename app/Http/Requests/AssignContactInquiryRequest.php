<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignContactInquiryRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assigned_to' => 'required|exists:users,id',
        ];
    }
}
