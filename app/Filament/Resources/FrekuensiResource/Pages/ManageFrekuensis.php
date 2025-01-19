<?php

namespace App\Filament\Resources\FrekuensiResource\Pages;

use App\Filament\Resources\FrekuensiResource;
use App\Models\Praktikan;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFrekuensis extends ManageRecords
{
    protected static string $resource = FrekuensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->action(function ($data) {

                    $frekuensi = \App\Models\Frekuensi::create([
                        'name'  =>  $data['name'],
                    ]);

                    $praktikans = $data['praktikans'];

                    foreach ($praktikans as $praktikan_id) {
                        $praktikan = Praktikan::find($praktikan_id);

                        $praktikan->update([
                            'frekuensi_id'  =>  $frekuensi->id
                        ]);
                    }
                }),
        ];
    }
}
