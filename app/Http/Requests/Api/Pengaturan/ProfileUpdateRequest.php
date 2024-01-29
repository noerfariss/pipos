<?php

namespace App\Http\Requests\Api\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'jenis_kelamin' => ['required'],
            'kota_id' => ['required'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'kota_id' => 'Kota'
        ];
    }
}
