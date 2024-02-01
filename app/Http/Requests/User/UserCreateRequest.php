<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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
            'nama' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'whatsapp' => ['required', 'numeric'],
            'foto' => ['nullable'],
            'alamat' => ['nullable'],
            'roles' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password' => Hash::make('password'),
        ]);
    }
}
