<?php

namespace App\Filament\Asisten\Resources\ScheduleResource\Pages;

use App\Filament\Asisten\Resources\ScheduleResource;
use App\Filament\Asisten\Resources\ScheduleResource\RelationManagers\AttendanceRelationManager;
use App\Models\Attendance;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount($record): void
    {
        // Jalankan mount bawaan Filament agar form & record ter-load
        parent::mount($record);

        // Cek apakah ada parameter praktikan_id
        $praktikanId = request()->query('praktikan_id');

        if ($praktikanId) {
            session()->put('chatbox_praktikan_id', $praktikanId);
        }
    }
}
