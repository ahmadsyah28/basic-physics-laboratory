<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('biodata_pengurus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama')->nullable(false);
            $table->string('jabatan')->nullable(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('biodata_pengurus');
    }
};
