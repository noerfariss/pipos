<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Banner extends Model
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
            ->useLogName('banner');
    }

    public static function boot()
    {
        parent::boot();

        if (app('request')->is('member/*')) {
            static::addGlobalScope('user', function ($e) {
                $user = Auth::id();

                if ($user) {
                    $e->where('user_id', $user);
                }
            });

            static::creating(function ($e) {
                $e->user_id = Auth::id();
            });
        }
    }
}
