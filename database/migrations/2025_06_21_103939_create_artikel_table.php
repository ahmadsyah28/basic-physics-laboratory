<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_acara')->nullable(false);
            $table->text('deskripsi')->nullable(false);
            $table->string('penulis')->nullable();
            $table->datetime('tanggal_acara')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artikel');
    }
};
