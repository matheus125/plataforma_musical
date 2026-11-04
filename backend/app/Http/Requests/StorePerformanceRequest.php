<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceRequest extends FormRequest
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
            'song_id' => ['required', 'integer', 'exists:songs,id'],
            'audio' => ['required', 'file', 'mimes:mp3,wav,webm,m4a', 'max:20480'],
            'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:3600'],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }
}
