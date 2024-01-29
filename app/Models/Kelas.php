<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kelas extends Model
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
            ->useLogName('kelas');
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
                // userid
                $e->user_id = Auth::id();

                // generate kode
                $e->kode = Kelas::where('user_id', Auth::id())->max('kode') + 1;
                $e->kode_label = 'PTK' . str_pad($e->kode, 5, '0', STR_PAD_LEFT);
            });
        }
    }
}
