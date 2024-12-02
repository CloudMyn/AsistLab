<?php

namespace App\Filament\Praktikan\Resources;

use App\Filament\Praktikan\Resources\ScheduleResource\Pages;
use App\Filament\Praktikan\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return "Jadwal Asistensi";
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = get_auth_user()->id; // Ubah dengan ID user yang dicari

        $schedules = Schedule::whereHas('attendances', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        return $schedules;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('topic')
                    ->label('Tema/Topik'),

                Forms\Components\TextInput::make('room')
                    ->label('Kelas/Ruangan'),

                Fieldset::make('Waktu')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal'),

                        Forms\Components\TimePicker::make('start_time')
                            ->label('Waktu Mulai'),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('Waktu Selesai'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Jadwal Asistensi')
                    ->description(function ($record) {
                        return $record->start_time . " - " . $record->end_time;
                    })
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('topic')
                    ->label('Topik')
                    ->searchable(),

                Tables\Columns\TextColumn::make('room')
                    ->label('Ruangan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
