<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'PENGATURAN_READ']);
        Permission::create(['name' => 'PENGATURAN_EDIT']);

        Permission::create(['name' => 'USERWEB_READ']);
        Permission::create(['name' => 'USERWEB_CREATE']);
        Permission::create(['name' => 'USERWEB_EDIT']);
        Permission::create(['name' => 'USERWEB_DELETE']);

        Permission::create(['name' => 'ROLE_READ']);
        Permission::create(['name' => 'ROLE_CREATE']);
        Permission::create(['name' => 'ROLE_EDIT']);
        Permission::create(['name' => 'ROLE_DELETE']);

        Permission::create(['name' => 'PERMISSION_READ']);
        Permission::create(['name' => 'PERMISSION_CREATE']);
        Permission::create(['name' => 'PERMISSION_EDIT']);
        Permission::create(['name' => 'PERMISSION_DELETE']);

        Permission::create(['name' => 'BANNER_READ']);
        Permission::create(['name' => 'BANNER_CREATE']);
        Permission::create(['name' => 'BANNER_EDIT']);
        Permission::create(['name' => 'BANNER_DELETE']);

        Permission::create(['name' => 'MEMBER_READ']);
        Permission::create(['name' => 'MEMBER_CREATE']);
        Permission::create(['name' => 'MEMBER_EDIT']);
        Permission::create(['name' => 'MEMBER_DELETE']);
        Permission::create(['name' => 'MEMBER_PRINT']);

        Permission::create(['name' => 'KATEGORI_READ']);
        Permission::create(['name' => 'KATEGORI_CREATE']);
        Permission::create(['name' => 'KATEGORI_EDIT']);
        Permission::create(['name' => 'KATEGORI_DELETE']);
        Permission::create(['name' => 'KATEGORI_PRINT']);

        Permission::create(['name' => 'PRODUK_READ']);
        Permission::create(['name' => 'PRODUK_CREATE']);
        Permission::create(['name' => 'PRODUK_EDIT']);
        Permission::create(['name' => 'PRODUK_DELETE']);
        Permission::create(['name' => 'PRODUK_PRINT']);

        Permission::create(['name' => 'SUPLIER_READ']);
        Permission::create(['name' => 'SUPLIER_CREATE']);
        Permission::create(['name' => 'SUPLIER_EDIT']);
        Permission::create(['name' => 'SUPLIER_DELETE']);
        Permission::create(['name' => 'SUPLIER_PRINT']);

        Permission::create(['name' => 'UNIT_READ']);
        Permission::create(['name' => 'UNIT_CREATE']);
        Permission::create(['name' => 'UNIT_EDIT']);
        Permission::create(['name' => 'UNIT_DELETE']);
        Permission::create(['name' => 'UNIT_PRINT']);

        Permission::create(['name' => 'STOKIN_READ']);
        Permission::create(['name' => 'STOKIN_CREATE']);
        Permission::create(['name' => 'STOKIN_PRINT']);

        Permission::create(['name' => 'STOKOUT_READ']);
        Permission::create(['name' => 'STOKOUT_CREATE']);
        Permission::create(['name' => 'STOKOUT_PRINT']);

        Permission::create(['name' => 'KASIR_READ']);


        $superadmin = Role::where('name', 'superadmin')->first();
        $admin = Role::where('name', 'admin')->first();
        $kasir = Role::where('name', 'kasir')->first();

        $permissions = Permission::all();

        // Superadmin
        $superadmin->syncPermissions($permissions);

        // Role admin
        $admin->syncPermissions([
            'BANNER_READ',
            'BANNER_CREATE',
            'BANNER_EDIT',
            'BANNER_DELETE',
            'MEMBER_READ',
            'MEMBER_CREATE',
            'MEMBER_EDIT',
            'MEMBER_DELETE',
            'MEMBER_PRINT',
            'KATEGORI_READ',
            'KATEGORI_CREATE',
            'KATEGORI_EDIT',
            'KATEGORI_DELETE',
            'KATEGORI_PRINT',
            'PRODUK_READ',
            'PRODUK_CREATE',
            'PRODUK_EDIT',
            'PRODUK_DELETE',
            'PRODUK_PRINT',
            'SUPLIER_READ',
            'SUPLIER_CREATE',
            'SUPLIER_EDIT',
            'SUPLIER_DELETE',
            'SUPLIER_PRINT',
            'UNIT_READ',
            'UNIT_CREATE',
            'UNIT_EDIT',
            'UNIT_DELETE',
            'UNIT_PRINT',
            'STOKIN_READ',
            'STOKIN_CREATE',
            'STOKIN_PRINT',
            'STOKOUT_READ',
            'STOKOUT_CREATE',
            'STOKOUT_PRINT',
            'PENGATURAN_READ',
            'PENGATURAN_EDIT',
            'KASIR_READ'
        ]);

        // Role Kasir
        $kasir->syncPermissions([
            'KASIR_READ',
            'MEMBER_READ',
            'MEMBER_CREATE',
            'MEMBER_EDIT',
            'MEMBER_PRINT',
            'KATEGORI_READ',
            'KATEGORI_CREATE',
            'KATEGORI_EDIT',
            'KATEGORI_PRINT',
            'PRODUK_READ',
            'PRODUK_CREATE',
            'PRODUK_EDIT',
            'PRODUK_PRINT',
            'UNIT_READ',
            'UNIT_CREATE',
            'UNIT_EDIT',
            'UNIT_PRINT',
            'STOKOUT_READ',
            'STOKOUT_CREATE',
            'STOKOUT_PRINT',
        ]);
    }
}
