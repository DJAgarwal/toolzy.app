<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstagramDownloaderRequest extends FormRequest
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
            'url' => [
                'required',
                'string',
                'max:1000',
                'url',
                function ($attribute, $value, $fail) {
                    $host = parse_url($value, PHP_URL_HOST);
                    if (!$host) {
                        $fail('The provided input is not a valid URL.');
                        return;
                    }

                    $host = strtolower($host);
                    $allowedHosts = ['instagram.com', 'www.instagram.com', 'm.instagram.com'];
                    
                    if (!in_array($host, $allowedHosts, true)) {
                        $fail('Only Instagram URLs (instagram.com) are supported. Links from YouTube, Facebook, or other platforms are not accepted.');
                        return;
                    }

                    $path = parse_url($value, PHP_URL_PATH) ?? '';
                    if (!preg_match('#/(reel|p|tv)/([A-Za-z0-9_-]+)#i', $path)) {
                        $fail('Please provide a valid Instagram Reel, Post, or IGTV video URL (e.g., https://www.instagram.com/reel/shortcode/).');
                    }
                },
            ],
        ];
    }

    /**
     * Custom message for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'url.required' => 'Please enter an Instagram video URL.',
            'url.url' => 'Please enter a valid Instagram URL.',
            'url.max' => 'The URL is too long.',
        ];
    }
}
