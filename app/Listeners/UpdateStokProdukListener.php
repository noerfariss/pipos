<?php

namespace App\Listeners;

use App\Events\StokEvent;
use App\Models\Produk;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStokProdukListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StokEvent $event): void
    {
        $tipe = $event->stok->tipe;
        $produk_id = $event->stok->produk_id;
        $currentStok = $event->stok->produk->stok;
        $newStok = $event->stok->qty;

        switch ($tipe) {
                // stok masukkk
            case 1:
                Produk::find($produk_id)->update([
                    'stok' => $currentStok + $newStok
                ]);
                break;

                // stok out dan penjualan
            case 2:
            case 3:
                // cek apakah stok yang diambil melebihi dari stok yang ada.
                if ($newStok > $currentStok) {
                    throw new Exception("Quantity melebihi dari stok yang ada", 1);
                    break;
                }

                Produk::find($produk_id)->update([
                    'stok' => $currentStok - $newStok
                ]);
                break;
        }
    }
}
