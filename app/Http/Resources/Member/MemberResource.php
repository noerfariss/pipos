<?php

namespace App\Http\Resources\Member;

use App\Http\Resources\Kota\KotaResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'nama' => $this->nama,
            'jenis kelamin' => genderResource($this->jenis_kelamin),
            'whatsapp' => $this->phone,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'kota' => $this->kota->kota,
            'status' => statusTable($this->status, false),
            'register' => Carbon::parse($this->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMMM YYYY HH:mm')
        ];
    }
}
