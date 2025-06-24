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
        Schema::create('pengujian_item', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('jenis_pengujian_id')->nullable(false);
            $table->uuid('pengujian_id')->nullable(false);
            $table->integer('jumlah_sampel')->default(1);
            $table->timestamps();

            $table->foreign('jenis_pengujian_id')->references('id')->on('jenis_pengujian')->onDelete('cascade');
            $table->foreign('pengujian_id')->references('id')->on('pengujian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengujian_item');
    }
};
