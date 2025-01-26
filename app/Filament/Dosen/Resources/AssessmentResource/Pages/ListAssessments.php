<?php

namespace App\Filament\Dosen\Resources\AssessmentResource\Pages;

use App\Filament\Dosen\Resources\AssessmentResource;
use App\Filament\Resources\AssesstmentResource\Widgets\AssestmentChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssessments extends ListRecords
{
    protected static string $resource = AssessmentResource::class;

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
