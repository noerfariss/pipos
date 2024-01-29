<?php

namespace App\Http\Requests\Suplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuplierUpdateRequest extends FormRequest
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
            'suplier' => ['required', 'min:3', Rule::unique('supliers', 'suplier')->ignore($this->id, 'id')],
        ];
    }
}
