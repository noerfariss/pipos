<?php

namespace App\Http\Resources\Kategori;

use App\Http\Resources\Buku\BukuResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBukuResource extends JsonResource
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
            'kategori' => $this->kategori,
            'buku' => BukuResource::collection($this->buku->sortByDesc('id')->take(5)),
        ];
    }
}
