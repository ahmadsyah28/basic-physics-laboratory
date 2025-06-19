<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('misi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pointMisi');
            $table->timestamps();
        });

        Schema::create('profil_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->string('namaLaboratorium');
            $table->text('tentangLaboratorium');
            $table->string('visi');
            $table->uuid('misiId');
            $table->foreign('misiId')->references('id')->on('misi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_laboratorium');
        Schema::dropIfExists('misi');
    }
};
