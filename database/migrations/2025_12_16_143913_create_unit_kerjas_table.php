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
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit'); // Contoh: MAN 1 Kota Gorontalo

            // Data Kepala Unit (Penandatangan)
            $table->string('nama_kepala')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->string('jabatan_kepala')->nullable(); // Contoh: Kepala Madrasah

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kerjas');
    }
};
