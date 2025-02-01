<?php

namespace App\Http\Controllers;

// library untuk mengubah HTML menjadi PDF
use Barryvdh\DomPDF\Facade\Pdf;

class PDFEksporter extends Controller
{
    /**
     * Method untuk mengekspor laporan penilaian dalam format PDF
     *
     * @param int $id ID jadwal penilaian
     * @return \Illuminate\Http\Response - Stream PDF
     */
    public function export_penilaian($id)
    {
        // [1] AMBIL DATA UTAMA DARI DATABASE
        // Mencari jadwal penilaian berdasarkan ID
        $assessment = \App\Models\AssessmentSchedule::find($id);

        // Membuat objek Carbon dari tanggal jadwal
        $carbon = \Carbon\Carbon::createFromDate($assessment->jadwal);

        // [2] PERSIAPAN DATA UTAMA
        // Membuat array data asistensi untuk ditampilkan di PDF
        $data_asistensi = [
            'mata kuliah'       =>  $assessment->mata_kuliah,         // Nama mata kuliah
            'semester'          =>  getSemester($assessment->created_at), // Semester menggunakan helper
            'tahun akademik'    =>  $assessment->created_at->year,    // Tahun akademik
            'frekuensi'         =>  $assessment->frekuensi,           // Frekuensi pertemuan
            'jadwal'            =>  $carbon->isoFormat('dddd, D MMMM Y'), // Format tanggal lengkap
            'asisten'           =>  $assessment->asisten,             // Nama asisten
            'topik'             =>  $assessment->topik,               // Topik asistensi
        ];

        // [3] FORMAT DATA TABEL PENILAIAN
        $formated_table = [];
        $index = 1;

        // Loop melalui setiap penilaian
        foreach ($assessment->assessment as $model) {
            // Format baris tabel untuk tiap praktikan
            $formated_table[] = [
                $index, // Nomor urut
                $model->attendance->praktikan->name, // Nama praktikan
                $model->score, // Nilai
                $model->comments ?? 'Tidak ada komentar', // Komentar atau placeholder
            ];
            $index++;
        }

        // [4] STRUKTUR DATA UNTUK VIEW PDF
        $data = [
            'title'     =>  'Laporan Penilaian Asistensi', // Judul dokumen
            'content'   =>  [
                'Informasi Asistensi' =>  $data_asistensi, // Data utama
            ],
            'tables'    =>  [
                'Penilaian Praktikan' =>  [
                    "kolom"     =>  ['No', 'Praktikan', 'Nilai', 'Komentar'], // Header tabel
                    "data"      =>  $formated_table, // Data tabel
                ]
            ]
        ];

        // [5] GENERATE PDF
        $pdf = Pdf::loadView('report.index', $data) // Load view template
            ->setPaper('a4', 'portrait'); // Set ukuran kertas

        // [6] RETURN HASIL SEBAGAI STREAM
        return $pdf->stream(); // Tampilkan PDF di browser
    }
}
