<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = 'nip';
    public $incrementing = false; // Karena primary key bukan auto-increment integer
    protected $keyType = 'string';

    protected $guarded = [];

    // Relasi ke pengajuan cuti
    public function pengajuan_cuti()
    {
        return $this->hasMany(PengajuanCuti::class, 'pegawai_nip', 'nip');
    }

    public function unit_kerja()
    {
        // Ini memberitahu Laravel bahwa 'unit_kerja_id' adalah kunci ke tabel UnitKerja
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
