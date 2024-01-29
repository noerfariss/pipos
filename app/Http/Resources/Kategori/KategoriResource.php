<?php

namespace App\Http\Resources\Kategori;

use App\Http\Resources\Buku\BukuResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KategoriResource extends JsonResource
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
            'kode' => $this->kode,
            'kategori' => $this->kategori,
            'is_app' => $this->is_app,
        ];
    }
}
