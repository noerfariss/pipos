<?php

namespace App\Http\Requests\Stok;

use App\Enums\StokEnums;
use Illuminate\Foundation\Http\FormRequest;

class StokOutRequest extends FormRequest
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
            'reason' => ['nullable'],
            'keterangan' => ['required_if:reason,Lainnya']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipe' => StokEnums::OUT->value
        ]);
    }

    public function attributes()
    {
        return [
            'produk_id' => 'Produk',
            'reason' => 'Alasan'
        ];
    }
}
