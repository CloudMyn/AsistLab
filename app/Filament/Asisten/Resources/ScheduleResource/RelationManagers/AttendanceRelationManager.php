<?php

namespace App\Filament\Asisten\Resources\ScheduleResource\RelationManagers;

use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class AttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'Kehadiran Praktikan';

    protected static ?string $modelLabel = 'Kehadiran Praktikan';

    public function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Praktikan')
                    ->placeholder('Pilih Praktikan')
                    ->columnSpanFull()
                    ->options(function (RelationManager $livewire) {
                        $role   = Role::where('name', 'praktikan')->first();

                        $scheduleId = $livewire->getOwnerRecord()->id;

                        return $role->users()->whereDoesntHave('attendances', function ($query) use ($scheduleId) {
                            $query->where('schedule_id', $scheduleId);
                        })->get()->pluck('name', 'id')->toArray();
                    })
                    ->distinct()
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('present')
            ->columns([
                Tables\Columns\TextColumn::make('praktikan.name')
                    ->label('Nama Praktikan'),

                Tables\Columns\ToggleColumn::make('present')
                    ->label('Kehadiran')
                    ->afterStateUpdated(function ($record, $state) {

                        Notification::make()
                            ->title('Berhasil')
                            ->body(ucwords('Kehadiran ' . $record->praktikan->name . ' berhasil diubah'))
                            ->success()
                            ->color('success')
                            ->send();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
