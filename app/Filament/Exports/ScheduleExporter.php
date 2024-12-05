<?php

namespace App\Filament\Exports;

use App\Models\Schedule;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ScheduleExporter extends Exporter
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('date')
                ->label('Tanggal'),
            ExportColumn::make('start_time')
                ->label('Waktu Mulai'),
            ExportColumn::make('end_time')
                ->label('Waktu Berakhir'),
            ExportColumn::make('topic')
                ->label('Topik'),
            ExportColumn::make('room')
                ->label('Ruang'),
            ExportColumn::make('asisten.name')
                ->label('Nama Asisten'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your schedule export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
