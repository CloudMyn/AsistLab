<?php

namespace App\Filament\Dosen\Resources\AssessmentScheduleResource\Pages;

use App\Filament\Dosen\Resources\AssessmentScheduleResource;
use App\Filament\Resources\AssesstmentResource\Widgets\AssestmentChart;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAssessmentSchedules extends ManageRecords
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
