<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadLoadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    public function rules(): array
    {
        return [
            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:10240', // 10MB
            ],
            'type' => ['required', Rule::in(array_column(DocumentType::cases(), 'value'))],
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Please select a file to upload.',
            'document.mimes' => 'Only PDF and image files (JPG, PNG, WebP) are allowed.',
            'document.max' => 'File size must not exceed 10MB.',
        ];
    }
}
