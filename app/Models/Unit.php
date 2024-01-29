<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Unit extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'unit_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($e) {
                return request()->ip();
            })
            ->useLogName('Unit');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $e->uuid = Str::uuid();
        });
    }
}
