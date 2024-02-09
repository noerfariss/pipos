<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\errorResource;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function index()
    {
        return view('member.index');
    }

    public function summaryTransaction(Request $request)
    {
        $tanggal = pecahTanggal($request->tanggal);

        try {
            // total
            $total = DB::table('transaksis as a')
                ->where(fn ($e) => $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]))
                ->count();

            // total items
            $items = DB::table('transaksis as a')
                ->select('items')
                ->where(fn ($e) => $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]))
                ->get()->pluck('items');

            $dataItems = [];
            foreach ($items as $item) {
                $decodeItems = json_decode($item);
                foreach ($decodeItems as $getItems) {
                    $dataItems[] = $getItems;
                }
            }

            $sumItems = collect($dataItems)->sum('qty');

            // total kategori
            $kategori = collect($dataItems)->groupBy('kategori')->count();


            // total member
            $member = DB::table('transaksis as a')
                ->whereNotNull('member_id')
                ->where(fn ($e) => $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]))
                ->count();

            $data = [
                'total' => $total,
                'items' => $sumItems,
                'categories' => $kategori,
                'members' => $member
            ];

            return new SuccessResource([
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    public function dailyTransaction(Request $request)
    {
        $tanggal = pecahTanggal($request->tanggal);

        try {
            $data = DB::table('transaksis as a')
                ->select(
                    DB::raw('DATE_FORMAT(a.created_at, "%m/%d") as tanggal'),
                    DB::raw('COUNT(a.id) as total')
                )
                ->where(function ($e) use ($tanggal) {
                    $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]);
                })
                ->groupBy(DB::raw('DATE(a.created_at)'))
                ->get();

            $getTotal = collect($data);


            // looping calendar
            $tmulai = Carbon::parse($tanggal[0]);
            $takhir = Carbon::parse($tanggal[1]);

            $periode = CarbonPeriod::create($tmulai, $takhir);

            $kalender = [];
            foreach ($periode as $period) {
                $tanggal = $period->isoFormat('MM/DD');
                $total = $getTotal->firstWhere('tanggal', $tanggal);
                $totalValue = $total ? $total->total : 0;

                $kalender[] = [
                    'tanggal' => $tanggal,
                    'total' => $totalValue
                ];
            }

            return new SuccessResource([
                'data' => $kalender
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    public function memberTransaction(Request $request)
    {
        $tanggal = pecahTanggal($request->tanggal);

        try {
            $total = DB::table('transaksis as a')
                ->where(fn ($e) => $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]))
                ->count();

            $umum = DB::table('transaksis as a')
                ->whereNull('member_id')
                ->where(fn ($e) => $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]))
                ->count();

            $member = DB::table('transaksis as a')
                ->whereNotNull('member_id')
                ->where(fn ($e) => $e->whereDate('a.created_at', '>=', $tanggal[0])->whereDate('a.created_at', '<=', $tanggal[1]))
                ->count();

            $data = [
                'total' => $total,
                'umum' => $umum,
                'member' => $member
            ];

            return new SuccessResource([
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }
}
