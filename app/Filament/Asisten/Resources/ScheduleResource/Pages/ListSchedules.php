<?php

namespace App\Filament\Asisten\Resources\ScheduleResource\Pages;

use App\Filament\Asisten\Resources\ScheduleResource;
use App\Filament\Asisten\Widgets\CalendarWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }
}
