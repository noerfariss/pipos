<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function items(): Attribute
    {
        return Attribute::make(
            set: fn (array $value) => json_encode($value),
        );
    }

    public function memberDetail(): Attribute
    {

        return Attribute::make(
            set: function ($value) {
                if (gettype($value) == 'array' || $value !== '' || $value !== null) {
                    return json_encode($value);
                } else {
                    return NULL;
                }
            }
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $tahun_sekarang = date('y'); // tahun 2024 --> 24
            $bulan_sekarang = date('n'); // bulan Mei --> 5
            $strBulan = date('m'); // bulan Mei --> 05

            $urut = Transaksi::query()->where('tahun', $tahun_sekarang)->where('bulan', $bulan_sekarang)->max('urut') + 1;

            $e->uuid = Str::uuid();
            $e->tahun = $tahun_sekarang;
            $e->bulan = $strBulan;
            $e->urut = $urut;

            $e->no_transaksi = 'INV' . $tahun_sekarang . $strBulan . str_pad($urut, 5, '0', STR_PAD_LEFT);
        });
    }
}
