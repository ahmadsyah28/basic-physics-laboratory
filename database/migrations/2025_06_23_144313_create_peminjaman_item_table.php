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
        Schema::create('peminjamanItem', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('peminjamanId');
            $table->uuid('alat_id');
            $table->integer('jumlah');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('peminjamanId')->references('id')->on('peminjaman')->onDelete('cascade');
            $table->foreign('alat_id')->references('id')->on('alat')->onDelete('cascade');

            // Indexes
            $table->index(['peminjamanId', 'alat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamanItem');
    }
};
