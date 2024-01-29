<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create(['kategori' => 'Pempes']);
        Kategori::create(['kategori' => 'Susu']);
        Kategori::create(['kategori' => 'Makanan']);
        Kategori::create(['kategori' => 'Mainan']);
        Kategori::create(['kategori' => 'Baju']);
        Kategori::create(['kategori' => 'Gendongan']);
        Kategori::create(['kategori' => 'Peralatan Makan']);
    }
}
