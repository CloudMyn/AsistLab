<?php

namespace App\Filament\Asisten\Resources\ScheduleResource\Pages;

use App\Filament\Asisten\Resources\ScheduleResource;
use App\Models\Attendance;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected static bool $canCreateAnother = false;

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        if (config('app.env') == 'local') {
            $data = Schedule::factory()->make()->toArray();

            $data['attendances'] = [[]];

            $this->form->fill($data);
        }


        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {

        $frekuensi_id  =   $data['frekuensi'];

        $frekuens = \App\Models\Frekuensi::find($frekuensi_id);

        $praktikans = $frekuens->praktikans;

        unset($data['frekuensi']);

        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        foreach ($praktikans as $praktikan) {
            Attendance::create([
                'schedule_id' => $record->id,
                'user_id' => $praktikan->user_id
            ]);
        }


        return $record;
    }
}
