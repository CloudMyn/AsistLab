<?php

namespace App\Filament\Asisten\Resources\AssessmentResource\Pages;

use App\Filament\Asisten\Resources\AssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssessment extends CreateRecord
{
    protected static string $resource = AssessmentResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
