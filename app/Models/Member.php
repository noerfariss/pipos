<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($e) {
                return request()->ip();
            })
            ->useLogName('Member');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $e->uuid = Str::uuid();
        });
    }
}
