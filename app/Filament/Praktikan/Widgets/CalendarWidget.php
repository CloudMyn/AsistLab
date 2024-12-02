<?php

namespace App\Filament\Praktikan\Widgets;


use App\Filament\Asisten\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class CalendarWidget extends \Saade\FilamentFullCalendar\Widgets\FullCalendarWidget
{
    public Model | string | null $model = Schedule::class;

    protected static ?int $sort = 2;

    protected function headerActions(): array
    {
        return [];
    }

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridYear,dayGridMonth,dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $userId = get_auth_user()->id; // Ubah dengan ID user yang dicari

        $schedules = Schedule::whereHas('attendances', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        return $schedules
            ->get()
            ->map(
                fn(Schedule $schedule) => [
                    'title' => 'Jadwal Asistensi #' . $schedule->id,
                    'start' => $schedule->date,
                    'end' => $schedule->date,
                    'url' => ScheduleResource::getUrl(name: 'edit', parameters: ['record' => $schedule]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }
}
