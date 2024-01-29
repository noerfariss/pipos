<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

class PengaturanPeminjamanUpdateRequest extends FormRequest
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
            'durasi_pinjam' => ['required', 'numeric'],
            'batas_pinjam' => ['required', 'numeric'],
            'denda_pinjam' => ['required', 'numeric'],
        ];
    }
}
