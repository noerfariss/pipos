<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::create([
            'nama' => env('SUPERUSER_NAMA'),
            'uuid' => Str::uuid(),
            'email' => env('SUPERUSER_EMAIL'),
            'password' => Hash::make(env('SUPERUSER_PASSWORD')),
            'whatsapp' => env('SUPERUSER_PHONE'),
            'alamat' => env('SUPERUSER_ALAMAT')
        ]);

        $admin = User::create([
            'nama' => 'ADMIN',
            'uuid' => Str::uuid(),
            'email' => 'admin@pitagoras.cloud',
            'password' => Hash::make('password'),
            'whatsapp' => '085171737359',
            'alamat' => 'Sidoarjo'
        ]);

        $kasir = User::create([
            'nama' => 'KASIR',
            'uuid' => Str::uuid(),
            'email' => 'kasir@pitagoras.cloud',
            'password' => Hash::make('password'),
            'whatsapp' => '085171737359',
            'alamat' => 'Sidoarjo'
        ]);

        $superadmin->syncRoles('superadmin');
        $admin->syncRoles('admin');
        $kasir->syncRoles('kasir');
    }
}
