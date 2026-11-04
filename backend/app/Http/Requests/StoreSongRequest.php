<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSongRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:songs,slug'],
            'artist' => ['required', 'string', 'max:160'],
            'genre' => ['nullable', 'string', 'max:80'],
            'language' => ['nullable', 'string', 'max:40'],
            'key' => ['nullable', 'string', 'max:12'],
            'musical_key' => ['nullable', 'string', 'max:12'],
            'bpm' => ['nullable', 'integer', 'min:40', 'max:260'],
            'difficulty_level' => ['required', 'integer', 'between:0,5'],
            'audio_url' => ['nullable', 'url', 'max:2048'],
            'cover_image_url' => ['nullable', 'url', 'max:2048'],
            'source_type' => ['nullable', 'string', 'max:40'],
            'license_type' => ['nullable', 'string', 'max:80'],
            'rights_holder' => ['nullable', 'string', 'max:255'],
            'available_countries' => ['nullable', 'array'],
            'available_countries.*' => ['string', 'size:2'],
            'lyrics' => ['nullable', 'string'],
            'lyrics_language' => ['nullable', 'string', 'max:12'],
            'vocal_range_min' => ['nullable', 'string', 'max:8'],
            'vocal_range_max' => ['nullable', 'string', 'max:8'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
