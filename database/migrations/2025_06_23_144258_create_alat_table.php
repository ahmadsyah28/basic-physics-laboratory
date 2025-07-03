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
            $table->string('kode')->unique();
            $table->text('deskripsi');
            $table->string('image_url');
            $table->integer('jumlah_tersedia');
            $table->integer('jumlah_dipinjam');
            $table->integer('jumlah_rusak');
            $table->string('nama_kategori');
            $table->integer('stok');
            $table->double('harga')->nullable();
            $table->timestamps();

            // Index untuk performa query (dibuat dulu sebelum foreign key)
            $table->index('nama_kategori');
            $table->index('kode');
        });

        // Tambahkan foreign key constraint setelah tabel dibuat
        Schema::table('alat', function (Blueprint $table) {
            $table->foreign('nama_kategori')
                  ->references('nama_kategori')
                  ->on('kategori_alat')
                  ->onDelete('restrict'); // Mencegah penghapusan kategori yang masih digunakan
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
