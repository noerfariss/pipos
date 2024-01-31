<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

class PengaturanUpdateRequest extends FormRequest
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
            'nama' => ['required', 'min:5'],
            'alamat' => ['required', 'min:6'],
            'foto' => ['nullable'],
            'phone' => ['required', 'numeric', 'min:5'],
            'phone2' => ['nullable', 'numeric', 'min:5'],
            'email' => ['required', 'email'],
            'timezone' => ['required'],
            'kota_id' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'logo' => $this->foto,
        ]);
    }

    public function attributes()
    {
        return [
            $this->kota_id => 'Kota'
        ];
    }
}
