<?php

namespace App\Http\Resources\Stok;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StokOutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tipe' => stokTipe($this->tipe),
            'kode produk' => $this->produk->barcode,
            'produk' => $this->produk->produk,
            'qty' => $this->qty,
            'suplier' => $this->suplier ? $this->suplier->suplier : '-',
            'keterangan' => $this->keterangan,
            'tanggal' => Carbon::parse($this->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMMM YYYY HH:mm'),
        ];
    }
}
