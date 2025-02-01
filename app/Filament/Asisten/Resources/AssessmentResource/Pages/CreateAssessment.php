<?php

namespace App\Filament\Asisten\Resources\AssessmentResource\Pages;

use App\Filament\Asisten\Resources\AssessmentResource;
use App\Models\AssessmentSchedule;
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

            $dosen_id   = $data['dosen_id'];

            unset($data['dosen_id']);

            $record = new ($this->getModel())($data);

            if (
                static::getResource()::isScopedToTenant() &&
                ($tenant = Filament::getTenant())
            ) {
                return $this->associateRecordWithTenant($record, $tenant);
            }

            $record->save();

            // Add logic to execute during the creating event

            $model = AssessmentSchedule::where('schedule_id', $record->schedule_id)->first();

            if (!$model) {

                $model = AssessmentSchedule::create([
                    'schedule_id' => $record->schedule_id,
                    'topik' => $record->schedule->topic,
                    'mata_kuliah' => $record->schedule?->mata_kuliah?->nama,
                    'frekuensi' => $record->attendance->praktikan->praktikan->frekuensi->name,
                    'jadwal' => $record->schedule->date,
                    'asisten' => $record->schedule->asisten->name,
                    'asisten_id' => $record->schedule->user_id,
                    'nilai' => $record->score,
                    'dosen_id' => $dosen_id,
                    'approver_id' => null,
                    'approved_at' => null,
                ]);
            } else {
                $model->update([
                    'nilai' => $record->score + $model->nilai,
                ]);
            }

            $record->update([
                'assessment_schedule_id' => $model->id
            ]);

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
