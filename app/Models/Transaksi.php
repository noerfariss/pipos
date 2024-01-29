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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($e) {
            $e->uuid = Str::uuid();
        });
    }
}
