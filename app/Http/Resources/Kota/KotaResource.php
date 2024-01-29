<?php

namespace App\Http\Resources\Kota;

use App\Http\Resources\Provinsi\ProvinsiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kota' => $this->kota,
            'provinsi' => new ProvinsiResource($this->provinsi),
        ];
    }
}
