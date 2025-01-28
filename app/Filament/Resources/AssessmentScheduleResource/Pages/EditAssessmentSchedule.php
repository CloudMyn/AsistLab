<?php

namespace App\Filament\Resources\AssessmentScheduleResource\Pages;

use App\Filament\Resources\AssessmentScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssessmentSchedule extends EditRecord
{
    protected static string $resource = AssessmentScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
