<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($e) {
                return request()->ip();
            })
            ->useLogName('kategori');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $kode = Kategori::query()->max('kode') + 1;

            $e->uuid = Str::uuid();
            $e->kode = $kode;
            $e->kode_label = 'KP' . str_pad($kode, 5, '0', STR_PAD_LEFT);
        });
    }
}
