<?php

// File: database/migrations/2025_06_21_103949_create_gambar_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gambar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pengurus_id')->nullable();
            $table->uuid('acara_id')->nullable();
            $table->uuid('facility_id')->nullable(); // TAMBAHAN untuk fasilitas
            $table->string('url')->nullable(false);
            $table->enum('kategori', ['PENGURUS', 'ACARA', 'FASILITAS']); // UPDATE enum
            $table->timestamps();

            // Foreign keys yang sudah ada
            $table->foreign('pengurus_id')->references('id')->on('biodata_pengurus')->onDelete('cascade');
            $table->foreign('acara_id')->references('id')->on('artikel')->onDelete('cascade');

            // JANGAN tambah foreign key facility_id di sini karena tabel facilities belum ada
            // Foreign key akan ditambah di migration terpisah setelah tabel facilities dibuat
        });
    }

    public function down()
    {
        Schema::dropIfExists('gambar');
    }
};
