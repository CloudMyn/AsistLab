<?php

namespace App\Filament\Resources\MatakuliahResource\Pages;

use App\Filament\Resources\FrekuensiResource;
use App\Filament\Resources\MatakuliahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatakuliahs extends ListRecords
{
    protected static string $resource = MatakuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah'),
            Actions\Action::make('frekuensi')
                ->label('Tabel Frekuensi')
                ->color('info')
                ->url(FrekuensiResource::getUrl('index')),
        ];
    }
}
