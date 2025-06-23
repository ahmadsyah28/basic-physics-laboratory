<?php

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
            $table->string('url')->nullable(false);
            $table->enum('kategori', ['PENGURUS', 'ACARA']);
            $table->timestamps();

            $table->foreign('pengurus_id')->references('id')->on('biodata_pengurus')->onDelete('cascade');
            $table->foreign('acara_id')->references('id')->on('artikel')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gambar');
    }
};
