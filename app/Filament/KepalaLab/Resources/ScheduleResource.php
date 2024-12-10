<?php

namespace App\Filament\KepalaLab\Resources;

use App\Filament\KepalaLab\Resources\ScheduleResource\Pages;
use App\Filament\KepalaLab\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\TextInput::make('topic')
                    ->label('Modul')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),

                Forms\Components\TextInput::make('room')
                    ->label('Kelas/Ruangan')
                    ->required()
                    ->maxLength(255),

                Fieldset::make('Waktu')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->columnSpanFull()
                            ->minDate(today())
                            ->required(),

                        Forms\Components\TimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->minDate(today())
                            ->required(),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->minDate(today())
                            ->required(),
                    ]),

                Repeater::make('attendances')
                    ->label('Daftar Praktikan')
                    ->relationship('attendances')
                    ->columnSpanFull()
                    ->minItems(1)
                    ->maxItems(10)
                    ->simple(
                        Forms\Components\Select::make('user_id')
                            ->label('Praktikan')
                            ->placeholder('Pilih Praktikan')
                            ->options(function (Builder $query) {
                                $role   = Role::where('name', 'praktikan')->first();
                                return $role->users()->pluck('name', 'id');
                            })
                            ->required()
                            ->distinct()
                            ->searchable(),
                    ),

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
                    ->searchable()
                    ->sortable()
                    ->badge(),

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
            ->defaultSort('date', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'view' => Pages\ViewSchedule::route('/{record}/view'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
