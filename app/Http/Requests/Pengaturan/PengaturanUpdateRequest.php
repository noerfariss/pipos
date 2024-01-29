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
            'sekolah' => ['required', 'min:5'],
            'npsn' => ['required', 'numeric', 'min:5'],
            'whatsapp' => ['required', 'numeric', 'min:5'],
            'alamat_sekolah' => ['required', 'min:6'],
            'logo' => ['nullable'],
            'telpon' => ['required', 'numeric', 'min:5'],
            'email' => ['required', 'email'],
            'timezone' => ['required'],
            'kecamatan' => ['required']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'kecamatan_id' => $this->kecamatan,
        ]);
    }
}
