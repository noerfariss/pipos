<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedTinyInteger('tahun')->nullable()->after('uuid');
            $table->unsignedTinyInteger('bulan')->nullable()->after('tahun');
            $table->unsignedBigInteger('urut')->nullable()->after('bulan');
            $table->string('no_transaksi')->nullable()->index()->after('urut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['tahun', 'bulan', 'urut', 'no_transaksi']);
        });
    }
};
