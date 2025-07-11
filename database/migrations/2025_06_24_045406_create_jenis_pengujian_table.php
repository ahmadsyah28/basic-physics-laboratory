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
        Schema::create('jenis_pengujian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_pengujian')->nullable(false);
            $table->integer('harga_per_sampel')->nullable(false);
            $table->boolean('is_available')->default(true);
            $table->text('deskripsi')->nullable();
            $table->string('durasi')->nullable();
            $table->json('aplikasi')->nullable(); // untuk menyimpan array aplikasi
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pengujian');
    }
};
