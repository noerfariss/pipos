<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);

        $this->call([
            ProvinsiSeeder::class,
            KotaSeeder::class,
            KecamatanSeeder::class,
            PengaturanSeeder::class,
        ]);

        $this->call([
            MemberSeeder::class,
            KategoriSeeder::class,
            SuplierSeeder::class,
            UnitSeeder::class,
            ProdukSeeder::class,
            // StokSeeder::class,
        ]);
    }
}
