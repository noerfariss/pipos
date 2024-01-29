<?php

namespace App\Console\Commands;

use App\Models\Denda as ModelsDenda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Denda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perpus:denda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk mentraking denda setiap harinya kepada si peminjam buku';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = Peminjaman::query()
            ->select('id', 'user_id')
            ->with('user', fn ($e) => $e->select('id', 'denda_pinjam'))
            ->where([
                'is_kembali' => false,
                'status' => true,
            ])
            ->whereDate('batas_pengembalian', '<', Carbon::now());

        if ($data->count() > 0) {
            DB::beginTransaction();
            try {
                $denda_arr = [];
                foreach ($data->get() as $item) {
                    $denda_arr[] = [
                        'peminjaman_id' => $item->id,
                        'denda' => $item->user->denda_pinjam,
                        'user_id' => $item->user->id,
                        'created_at' => Carbon::now(),
                    ];
                }

                activity()
                    ->useLog('cron denda')
                    ->log(request()->ip());

                ModelsDenda::insert($denda_arr);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::warning($th->getMessage());
            }

        } else {
            activity()
                ->useLog('cron denda | Tidak ada denda')
                ->log(request()->ip());
        }
    }
}
