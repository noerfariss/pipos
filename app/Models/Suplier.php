<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Suplier extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($e) {
                return request()->ip();
            })
            ->useLogName('suplier');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $kode = Suplier::query()->max('kode') + 1;

            $e->uuid = Str::uuid();
            $e->kode = $kode;
            $e->kode_label = 'KS' . str_pad($kode, 5, '0', STR_PAD_LEFT);

        });
    }
}
