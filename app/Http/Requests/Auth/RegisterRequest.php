<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'whatsapp' => ['required', 'digits_between:6,15'],
            'sekolah' => ['required', 'min:5'],
            'npsn' => ['required', 'numeric', 'min:5'],
            'telpon' => ['required', 'numeric', 'min:6'],
            'password' => ['required', 'min:6', 'confirmed']
        ];
    }
}
