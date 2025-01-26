<?php

namespace Database\Seeders;

use App\Models\Frekuensi;
use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrekuensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar frekuensi beserta mata kuliah yang terkait
        $frekuensiList = [
            'TI_ALPRO1-1' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-2' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-3' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-4' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-5' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-6' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-7' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-8' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-9' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-10' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-11' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-12' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-13' => 'Algoritma dan Pemrograman 1',
            'TI_ALPRO1-14' => 'Algoritma dan Pemrograman 1',
            'SI_ALPRO-1' => 'Algoritma Pemrograman',
            'SI_ALPRO-2' => 'Algoritma Pemrograman',
            'SI_AA-1' => 'Aplikasi Akuntansi',
            'SI_AA-2' => 'Aplikasi Akuntansi',
            'SI_AA-3' => 'Aplikasi Akuntansi',
            'SI_BD2-1' => 'Basis Data II',
            'TI_BD2-1' => 'Basis Data II',
            'TI_BD2-2' => 'Basis Data II',
            'TI_BD2-3' => 'Basis Data II',
            'TI_BD2-4' => 'Basis Data II',
            'TI_BD2-5' => 'Basis Data II',
            'TI_BD2-6' => 'Basis Data II',
            'TI_BD2-7' => 'Basis Data II',
            'TI_BD2-8' => 'Basis Data II',
            'TI_BD2-9' => 'Basis Data II',
            'TI_BD2-10' => 'Basis Data II',
            'TI_BD2-11' => 'Basis Data II',
            'TI_BD2-12' => 'Basis Data II',
            'TI_BD2-13' => 'Basis Data II',
            'TI_BD2-14' => 'Basis Data II',
            'TI_BD2-15' => 'Basis Data II',
            'SI_BD2-2' => 'Basis Data II',
            'SI_BD2-3' => 'Basis Data II',
            'SI_JARKOM-1' => 'Jaringan Komputer',
            'SI_JARKOM-2' => 'Jaringan Komputer',
            'TI_MICRO-1' => 'Microcontroller',
            'TI_MICRO-2' => 'Microcontroller',
            'TI_MICRO-3' => 'Microcontroller',
            'TI_MICRO-4' => 'Microcontroller',
            'TI_MICRO-5' => 'Microcontroller',
            'TI_MICRO-6' => 'Microcontroller',
            'TI_MICRO-7' => 'Microcontroller',
            'TI_MICRO-8' => 'Microcontroller',
            'TI_MICRO-9' => 'Microcontroller',
            'TI_MICRO-10' => 'Microcontroller',
            'TI_MICRO-11' => 'Microcontroller',
            'TI_MICRO-12' => 'Microcontroller',
            'TI_MICRO-13' => 'Microcontroller',
            'TI_MICRO-14' => 'Microcontroller',
            'TI_MICRO-15' => 'Microcontroller',
            'TI_MOBILE-1' => 'Pemrograman Mobile',
            'SI_WEB-1' => 'Pemrograman Web',
            'SI_WEB-2' => 'Pemrograman Web',
            'TI_PTI-1' => 'Pengantar Teknologi Informasi',
            'TI_PTI-2' => 'Pengantar Teknologi Informasi',
            'TI_PTI-3' => 'Pengantar Teknologi Informasi',
            'TI_PTI-4' => 'Pengantar Teknologi Informasi',
            'TI_PTI-5' => 'Pengantar Teknologi Informasi',
            'TI_PTI-6' => 'Pengantar Teknologi Informasi',
            'TI_PTI-7' => 'Pengantar Teknologi Informasi',
            'TI_PTI-8' => 'Pengantar Teknologi Informasi',
            'TI_PTI-9' => 'Pengantar Teknologi Informasi',
            'TI_PTI-10' => 'Pengantar Teknologi Informasi',
            'TI_PTI-11' => 'Pengantar Teknologi Informasi',
            'TI_PTI-12' => 'Pengantar Teknologi Informasi',
            'TI_PTI-13' => 'Pengantar Teknologi Informasi',
            'TI_PTI-14' => 'Pengantar Teknologi Informasi',
            'SI_STI-1' => 'Sistem dan Teknologi Informasi',
            'SI_STI-2' => 'Sistem dan Teknologi Informasi',
            'SI_SO-1' => 'Sistem Operasi',
            'SI_SO-2' => 'Sistem Operasi',
            'SI_SO-3' => 'Sistem Operasi',
            'TI_SD-1' => 'Struktur Data',
            'TI_SD-2' => 'Struktur Data',
            'TI_SD-3' => 'Struktur Data',
            'TI_SD-4' => 'Struktur Data',
            'TI_SD-5' => 'Struktur Data',
            'TI_SD-6' => 'Struktur Data',
            'TI_SD-7' => 'Struktur Data',
            'TI_SD-8' => 'Struktur Data',
            'TI_SD-9' => 'Struktur Data',
            'TI_SD-10' => 'Struktur Data',
            'TI_SD-11' => 'Struktur Data',
            'TI_SD-12' => 'Struktur Data',
            'TI_SD-13' => 'Struktur Data',
            'TI_SD-14' => 'Struktur Data',
        ];

        // Masukkan data ke dalam tabel frekuensi
        foreach ($frekuensiList as $name => $mataKuliahName) {
            // Cari mata kuliah berdasarkan nama
            $mataKuliah = MataKuliah::where('nama', $mataKuliahName)->first();

            if ($mataKuliah) {
                Frekuensi::create([
                    'name' => $name,
                    'mata_kuliah_id' => $mataKuliah->id,
                ]);
            }
        }
    }
}
