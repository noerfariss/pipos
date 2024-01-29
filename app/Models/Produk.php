<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($e) {
                return request()->ip();
            })
            ->useLogName('Produk');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $e->uuid = Str::uuid();
        });
    }
}
