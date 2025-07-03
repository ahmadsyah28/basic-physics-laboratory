<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('namaPeminjam');
            $table->boolean('is_mahasiswa_usk')->default(true); // default sesuai konteks umum
            $table->string('noHp');
            $table->string('tujuanPeminjaman')->nullable();
            $table->dateTime('tanggal_pinjam')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('tanggal_pengembalian');
            $table->string('kondisi_pengembalian')->nullable();
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();

            // Indexes untuk optimasi query
            $table->index('status');
            $table->index('tanggal_pinjam');
            $table->index('tanggal_pengembalian');
            $table->index('is_mahasiswa_usk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
