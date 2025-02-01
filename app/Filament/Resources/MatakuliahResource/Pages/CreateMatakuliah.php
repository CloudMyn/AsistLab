<?php

namespace App\Filament\Resources\MatakuliahResource\Pages;

use App\Filament\Resources\MatakuliahResource;
use App\Models\Frekuensi;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMatakuliah extends CreateRecord
{
    protected static string $resource = MatakuliahResource::class;


    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        try {
            $frekuensis = $data['frekuensis'] ?? [];

            unset($data['frekuensis']);

            $record->save();

            foreach ($frekuensis as $frekuensi_id) {
                $frekuensi = Frekuensi::find($frekuensi_id);

                $frekuensi->update([
                    'mata_kuliah_id'  =>  $record->id
                ]);
            }
        } catch (\Throwable $th) {
        }

        return $record;
    }
}
