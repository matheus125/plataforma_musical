<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'username' => ['nullable', 'string', 'min:3', 'max:40', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'locale' => ['nullable', 'string', 'max:12'],
            'country' => ['nullable', 'string', 'size:2'],
            'timezone' => ['nullable', 'string', 'max:64'],
            'signup_source' => ['nullable', 'string', 'max:80'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
