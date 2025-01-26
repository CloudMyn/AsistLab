<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Exports\ScheduleExporter;
use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah'),
            ExportAction::make()
                ->label('Ekspor')
                ->exporter(ScheduleExporter::class)
        ];
    }
}
