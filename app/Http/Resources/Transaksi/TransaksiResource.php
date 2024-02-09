<?php

namespace App\Http\Resources\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'no_transaksi' => $this->no_transaksi,
            'total' => $this->total,
            'bayar' => $this->bayar,
            'kembali' => $this->kembali,
            'item' => json_decode($this->items),
            'member' => $this->member_id ? json_decode($this->member_detail) : []
        ];
    }
}
