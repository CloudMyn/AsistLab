<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\Schedule;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Pengguna', User::count())
                ->icon('heroicon-o-users'),
            Stat::make('Jumlah Asistensi', Schedule::count())
                ->icon('heroicon-o-calendar'),
            Stat::make('Total Penilaian', Assessment::sum('score'))
                ->icon('heroicon-o-chart-pie'),
        ];
    }
}
