<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar mata kuliah
        $mataKuliahList = [
            'Algoritma dan Pemrograman 1',
            'Algoritma Pemrograman',
            'Aplikasi Akuntansi',
            'Basis Data II',
            'Jaringan Komputer',
            'Microcontroller',
            'Pemrograman Mobile',
            'Pemrograman Web',
            'Pengantar Teknologi Informasi',
            'Sistem dan Teknologi Informasi',
            'Sistem Operasi',
            'Struktur Data',
        ];

        // Masukkan data ke dalam tabel mata_kuliah
        foreach ($mataKuliahList as $nama) {
            MataKuliah::create([
                'nama' => ucwords($nama),
            ]);
        }
    }
}
