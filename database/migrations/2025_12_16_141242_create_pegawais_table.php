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
        Schema::create('pegawais', function (Blueprint $table) {
            // NIP sebagai Primary Key karena unik dan digunakan di form
            $table->string('nip', 20)->primary();
            $table->string('nama_lengkap');
            $table->string('jabatan')->nullable();
            $table->string('masa_kerja')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();

            // Sisa Cuti (N = Tahun ini, N-1 = Tahun lalu, dst)
            $table->integer('sisa_cuti_n')->default(12);
            $table->integer('sisa_cuti_n_1')->default(0);
            $table->integer('sisa_cuti_n_2')->default(0);

            // Data Atasan Langsung (Disimpan di sini agar auto-fill saat form dibuka)
            $table->string('nama_atasan_langsung')->nullable();
            $table->string('nip_atasan_langsung')->nullable();
            $table->string('jabatan_atasan_langsung')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
