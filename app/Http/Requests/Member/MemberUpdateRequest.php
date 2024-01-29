<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberUpdateRequest extends FormRequest
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
            'foto' => ['nullable'],
            'phone' => ['required', 'numeric', Rule::unique('members', 'phone')->ignore($this->id, 'id')],
            'email' => ['nullable', 'email', Rule::unique('members', 'email')->ignore($this->id, 'id')],
            'jenis_kelamin' => ['required'],
            'alamat' => ['nullable'],
            'kota_id' => ['required']
        ];
    }

    public function attributes()
    {
        return [
            'kota_id' => 'Kota',
            'phone' => 'Whatsapp'
        ];
    }
}
