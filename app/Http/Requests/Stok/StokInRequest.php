<?php

namespace App\Http\Requests\Stok;

use App\Enums\StokEnums;
use Illuminate\Foundation\Http\FormRequest;

class StokInRequest extends FormRequest
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
            'produk_id' => ['required'],
            'qty' => ['required', 'numeric', 'min:1'],
            'suplier_id' => ['nullable'],
            'keterangan' => ['required_if:suplier_id,null']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipe' => StokEnums::IN->value
        ]);
    }

    public function attributes()
    {
        return [
            'suplier_id' => 'Suplier',
            'produk_id' => 'Produk'
        ];
    }

    public function messages()
    {
        return [
            'keterangan.required_if' => 'Keterangan wajib diisi jika Suplier tidak dipilih'
        ];
    }
}
