<?php

namespace App\Http\Requests\Produk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdukCreateRequest extends FormRequest
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
            'kategori_id' => ['required'],
            'barcode' => ['required', Rule::unique('produks', 'barcode'), 'min:3'],
            'produk' => ['required'],
            'keterangan' => ['nullable'],
            'harga' => ['required', 'numeric'],
            'stok_warning' => ['nullable', 'numeric'],
            'foto' => ['nullable'],
            'unit_id' => ['required'],
            'is_app' => ['nullable']
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->stok_warning == '' || $this->stok_warning == null) {
            $this->merge([
                'stok_warning' => 0,
            ]);
        }

        if (request()->exists('is_app')) {
            $this->merge([
                'is_app' => true,
            ]);
        } else {
            $this->merge([
                'is_app' => false,
            ]);
        }
    }

    public function attributes()
    {
        return [
            'barcode' => 'Kode',
            'unit_id' => 'Unit',
            'kategori_id' => 'Kategori'
        ];
    }
}
