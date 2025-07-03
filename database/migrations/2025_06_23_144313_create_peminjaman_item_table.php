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
            $table->integer('jumlah')->unsigned(); // Tambahkan unsigned untuk memastikan nilai positif
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('peminjamanId')
                  ->references('id')
                  ->on('peminjaman')
                  ->onDelete('cascade');

            $table->foreign('alat_id')
                  ->references('id')
                  ->on('alat')
                  ->onDelete('cascade');

            // Composite index untuk query yang sering digunakan
            $table->index(['peminjamanId', 'alat_id']);

            // Individual indexes untuk query terpisah
            $table->index('peminjamanId');
            $table->index('alat_id');

            // Unique constraint untuk mencegah duplikasi item dalam satu peminjaman
            $table->unique(['peminjamanId', 'alat_id'], 'unique_peminjaman_alat');
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
