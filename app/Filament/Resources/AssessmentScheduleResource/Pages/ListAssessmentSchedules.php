<?php

namespace App\Filament\Resources\AssessmentScheduleResource\Pages;

use App\Filament\Resources\AssessmentScheduleResource;
use App\Filament\Resources\AssesstmentResource\Widgets\AssestmentChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssessmentSchedules extends ListRecords
{
    protected static string $resource = AssessmentScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah'),
        ];
    }



    protected function getFooterWidgets(): array
    {
        return [
            AssestmentChart::class
        ];
    }
}
