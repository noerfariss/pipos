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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('barcode')->index()->unique()->nullable();
            $table->string('produk');
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('harga')->default(0);
            $table->unsignedInteger('stok_warning')->default(0);
            $table->string('foto')->nullable();
            $table->boolean('is_app')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
