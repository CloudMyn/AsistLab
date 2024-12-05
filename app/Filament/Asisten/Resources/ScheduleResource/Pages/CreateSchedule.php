<?php

namespace App\Filament\Asisten\Resources\ScheduleResource\Pages;

use App\Filament\Asisten\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

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
}
