<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PdfConverterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,doc,docx|max:10240', // 10MB limit
            'type' => 'required|string|in:pdf-to-jpg,jpg-to-pdf,pdf-to-word,word-to-pdf',
        ];
    }
}
