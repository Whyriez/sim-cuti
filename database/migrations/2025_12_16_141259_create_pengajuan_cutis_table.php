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
        Schema::create('pengajuan_cutis', function (Blueprint $table) {
            $table->id();

            $table->string('pegawai_nip', 25);
            $table->foreign('pegawai_nip')->references('nip')->on('pegawais')->onDelete('cascade');

            $table->date('tanggal_pengajuan');
            $table->string('jenis_cuti');
            $table->text('alasan_cuti');
            $table->integer('lama_cuti');
            $table->date('mulai_tanggal');
            $table->date('sampai_tanggal');

            $table->text('alamat_selama_cuti')->nullable();
            $table->string('telepon_selama_cuti')->nullable();

            // --- FILE UPLOAD ---
            $table->string('ttd_path')->nullable();  // Tanda tangan digital
            $table->string('foto_path')->nullable();  // Foto selfie/webcam
            $table->string('file_bukti_path')->nullable(); // <--- TAMBAHAN: File PDF Bukti (Surat Dokter, dll)

            $table->string('status')->default('pending');

            // Snapshot Data Atasan & Pejabat
            $table->string('nama_atasan_langsung')->nullable();
            $table->string('nip_atasan_langsung')->nullable();
            $table->string('jabatan_atasan_langsung')->nullable();

            $table->string('nama_pejabat')->nullable();
            $table->string('nip_pejabat')->nullable();
            $table->string('jabatan_pejabat')->nullable();

            $table->string('keputusan_pejabat')->nullable();
            $table->string('catatan_pejabat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cutis');
    }
};
