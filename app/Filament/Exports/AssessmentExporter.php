<?php

namespace App\Filament\Exports;

use App\Models\Assessment;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AssessmentExporter extends Exporter
{
    protected static ?string $model = Assessment::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('schedule.date')
                ->label('Jadwal Assistensi'),
            ExportColumn::make('schedule.asisten.name')
                ->label('Nama Asisten'),
            ExportColumn::make('attendance.praktikan.name')
                ->label('Nama Praktikan'),
            ExportColumn::make('score')
                ->label('Nilai'),
            ExportColumn::make('comments')
                ->label('Komentar'),
            ExportColumn::make('created_at')
                ->label('Dibuat Pada'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui Pada'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your assessment export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
