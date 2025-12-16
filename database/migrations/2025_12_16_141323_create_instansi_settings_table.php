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
        Schema::create('instansi_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi')->default('Kementerian Agama Kota Gorontalo');
            $table->string('nama_kepala')->nullable(); // Pejabat Berwenang
            $table->string('nip_kepala')->nullable();
            $table->string('jabatan_kepala')->default('Kepala Kantor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi_settings');
    }
};
