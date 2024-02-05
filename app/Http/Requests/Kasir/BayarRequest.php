<?php

namespace App\Http\Requests\Kasir;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class BayarRequest extends FormRequest
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
            'total' => ['required', 'numeric'],
            'bayar' => ['required', 'numeric'],
            'kembali' => ['required', 'numeric'],
            'items' => ['required'],
            'member' => ['nullable'],
            'member_id' => ['nullable'],
            'member_detail' => ['nullable']
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->member) {
            $member = Member::where('uuid', $this->member)->first();

            $this->merge([
                'member_id' => $member->id,
                'member_detail' => [
                    'nama' => $member->nama,
                    'phone' => $member->phone,
                    'email' => $member->email,
                    'jenis_kelamin' => genderResource($member->jenis_kelamin),
                    'kota' => $member->kota->kota
                ],
            ]);
        }
    }
}
