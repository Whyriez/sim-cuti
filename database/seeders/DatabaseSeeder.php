<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'), // Password: password
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Cuti',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('123'), // Password: password
            'role' => 'petugas',
        ]);

        // 2. Buat Data Dummy Kepala Instansi (Agar PDF tidak error saat awal)
        DB::table('instansi_settings')->insert([
            'nama_instansi' => 'Kementerian Agama Kota Gorontalo',
            'nama_kepala' => 'Dr. H. Contoh Kepala, M.Pd',
            'nip_kepala' => '198001012000121001',
            'jabatan_kepala' => 'Kepala Kantor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
