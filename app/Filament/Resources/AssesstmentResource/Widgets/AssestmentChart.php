<?php

namespace App\Filament\Resources\AssesstmentResource\Widgets;

use App\Models\Assessment;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssestmentChart extends ChartWidget
{
    protected static ?string $heading = 'Laporan Nilai Praktikan';

    protected array|string|int $columnSpan = 'full';

    protected static ?string $description = 'Grafik Rata-Rata Penilaian Asistensi Praktikan dari 0 - 100';

    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $usersWithAverageScore = DB::table('users')
            ->join('attendances', 'users.id', '=', 'attendances.user_id')
            ->join('assessments', 'attendances.id', '=', 'assessments.attendance_id')
            ->select('users.name', DB::raw('AVG(assessments.score) as average_score'))
            ->groupBy('users.name')
            ->get();

        // Siapkan data untuk chart Filament
        $labels = $usersWithAverageScore->pluck('name')->toArray();
        $data = $usersWithAverageScore->pluck('average_score')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Nilai Rata-Rata',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
