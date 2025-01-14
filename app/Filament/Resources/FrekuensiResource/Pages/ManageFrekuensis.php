<?php

namespace App\Filament\Resources\FrekuensiResource\Pages;

use App\Filament\Resources\FrekuensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFrekuensis extends ManageRecords
{
    protected static string $resource = FrekuensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
