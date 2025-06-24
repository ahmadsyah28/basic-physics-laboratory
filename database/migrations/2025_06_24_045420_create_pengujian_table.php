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
        Schema::create('pengujian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_penguji')->nullable(false)->comment('NAMA ATAU INSTANSI PENGUJI');
            $table->string('no_hp_penguji')->nullable(false);
            $table->string('email_penguji')->nullable(false);
            $table->string('organisasi_penguji')->nullable(false);
            $table->text('deskripsi')->nullable(false);
            $table->text('deskripsi_sampel')->nullable();
            $table->integer('total_harga')->nullable(false)->default(0);
            $table->datetime('tanggal_pengujian')->nullable(false)->useCurrent();
            $table->date('tanggal_diharapkan')->nullable();
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengujian');
    }
};
