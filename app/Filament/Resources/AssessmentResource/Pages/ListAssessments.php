<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Exports\AssessmentExporter;
use App\Filament\Resources\AssessmentResource;
use App\Filament\Resources\AssesstmentResource\Widgets\AssestmentChart;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListAssessments extends ListRecords
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->label('Ekspor')
                ->exporter(AssessmentExporter::class)
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AssestmentChart::class
        ];
    }
}
