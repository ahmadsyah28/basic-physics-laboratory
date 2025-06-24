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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('namaPeminjam');
            $table->string('noHp');
            $table->string('tujuanPeminjaman')->nullable();
            $table->datetime('tanggal_pinjam');
            $table->datetime('tanggal_pengembalian');
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();

            // Additional columns for better management
            $table->string('email')->nullable();
            $table->string('nim_nip')->nullable();

            // Indexes
            $table->index('status');
            $table->index('tanggal_pinjam');
            $table->index('tanggal_pengembalian');
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
