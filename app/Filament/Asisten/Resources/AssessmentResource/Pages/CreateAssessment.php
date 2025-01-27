<?php

namespace App\Filament\Asisten\Resources\AssessmentResource\Pages;

use App\Filament\Asisten\Resources\AssessmentResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAssessment extends CreateRecord
{
    protected static string $resource = AssessmentResource::class;

    protected static bool $canCreateAnother = true;


    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        try {
            $record = new ($this->getModel())($data);

            if (
                static::getResource()::isScopedToTenant() &&
                ($tenant = Filament::getTenant())
            ) {
                return $this->associateRecordWithTenant($record, $tenant);
            }

            $record->save();

            return $record;
        } catch (\Throwable $th) {

            Notification::make()
                ->title('Terjadi Kesalahan')
                ->body('Praktikan sudah memiliki penilaian di jadwal ini!')
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
