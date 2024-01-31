<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $guarded = [];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
