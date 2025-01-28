<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFEksporter extends Controller
{
    public function export_penilaian($id)
    {
        $assessment = \App\Models\AssessmentSchedule::find($id);

        $user = get_auth_user();

        $carbon = \Carbon\Carbon::createFromDate($assessment->jadwal);

        $data_asistensi  =   [
            'mata kuliah'       =>  $assessment->mata_kuliah,
            'semester'          =>  getSemester($assessment->created_at),
            'tahun akademik'    =>  $assessment->created_at->year,
            'frekuensi'         =>  $assessment->frekuensi,
            'jadwal'            =>  $carbon->isoFormat('dddd, D MMMM Y'),
            'asisten'           =>  $assessment->asisten,
            'topik'             =>  $assessment->topik,
            // 'total nilai'   =>  $assessment->nilai,
            // 'Disetujui'     =>  $assessment->approved_at ?? 'Belum Disetujui',
        ];

        $formated_table =   [];

        $index  =   1;
        foreach ($assessment->assessment as $model) {
            $formated_table[] =   [
                $index,
                $model->attendance->praktikan->name,
                $model->score,
                $model->comments ?? 'Tidak ada komentar',
            ];

            $index++;
        }

        $data   =   [
            'title'     =>  'Laporan Penilaian Asistensi',
            'content'   =>  [
                'Informasi Asistensi'     =>  $data_asistensi,
            ],
            'tables'    =>  [
                'Penilaian Praktikan'    =>  [
                    "kolom"     =>  ['No', 'Praktikan', 'Nilai', 'Komentar'],
                    "data"      =>  $formated_table,
                ]
            ]
        ];

        $pdf = Pdf::loadView('report.index', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream();
    }
}
