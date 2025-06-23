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
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('namaPengunjung')->comment('NAMA ATAU INSTANSI PENGUNJUNG');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('institution');
            $table->text('tujuan');
            $table->date('tanggal_kunjungan');
            $table->time('waktu_kunjungan');
            $table->integer('jumlahPengunjung')->default(1)->comment('JUMLAH ROMBONGAN YANG DIBAWA');
            $table->text('catatan_tambahan')->nullable();
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();

            // Index untuk pencarian berdasarkan tanggal dan waktu
            $table->index(['tanggal_kunjungan', 'waktu_kunjungan']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
