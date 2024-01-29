<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function provinsi()
    {
        return $this->kota->provinsi;
    }
}
