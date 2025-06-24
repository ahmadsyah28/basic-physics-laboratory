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
        Schema::create('alat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->text('deskripsi');
            $table->integer('stok');
            $table->boolean('isBroken')->default(false);
            $table->timestamps();

            // Additional columns for equipment management
            $table->string('model')->nullable();
            $table->string('kategori')->nullable();
            $table->string('gambar')->nullable();
            $table->json('spesifikasi')->nullable();
            $table->json('persyaratan')->nullable();
            $table->string('durasi_pinjam')->nullable();
            $table->string('icon')->nullable();

            // Indexes
            $table->index('kategori');
            $table->index('isBroken');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
