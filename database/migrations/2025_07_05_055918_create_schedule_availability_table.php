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
        Schema::create('schedule_availability', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date')->comment('Tanggal jadwal');
            $table->time('time_slot')->comment('Slot waktu (format: HH:MM)');
            $table->boolean('is_available')->default(true)->comment('Status ketersediaan slot');
            $table->string('reason')->nullable()->comment('Alasan jika tidak tersedia (maintenance, rapat, dll)');
            $table->text('notes')->nullable()->comment('Catatan tambahan dari admin');
            $table->timestamps();

            // Composite unique index untuk mencegah duplikasi date + time_slot
            $table->unique(['date', 'time_slot'], 'unique_date_time_slot');

            // Index untuk pencarian dan performa
            $table->index(['date', 'is_available'], 'idx_date_availability');
            $table->index('is_available', 'idx_availability');
            $table->index('date', 'idx_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_availability');
    }
};
