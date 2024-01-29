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
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->string('nama')->nullable();
            $table->string('logo')->nullable();
            $table->string('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->foreignId('kota_id')->nullable()->constrained('kotas')->onDelete('cascade');
            $table->enum('timezone', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'])->default('Asia/Jakarta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
