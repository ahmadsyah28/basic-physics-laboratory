<?php

// File: database/migrations/xxxx_xx_xx_xxxxxx_add_facility_foreign_key_to_gambar_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('gambar', function (Blueprint $table) {
            // Tambah foreign key untuk facility_id setelah tabel facilities sudah ada
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('gambar', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
        });
    }
};
