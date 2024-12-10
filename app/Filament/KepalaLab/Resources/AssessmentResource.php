<?php

namespace App\Filament\KepalaLab\Resources;

use App\Filament\KepalaLab\Resources\AssessmentResource\Pages;
use App\Filament\KepalaLab\Resources\AssessmentResource\RelationManagers;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return "Penilaian Asistensi";
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
                Forms\Components\Select::make('schedule_id')
                    ->label('Jadwal')
                    ->required()
                    ->live()
                    ->reactive()
                    ->placeholder('Pilih Jadwal')
                    ->options(function ($livewire) {
                        $schedules = Schedule::where('user_id', get_auth_user()->id)->get();

                        $array = [];

                        foreach ($schedules as $schedule) {
                            $array[$schedule->id] = $schedule->date . " - " . $schedule->room . " ( " . $schedule->topic . " ) ";
                        }

                        return $array;
                    }),

                Forms\Components\Select::make('attendance_id')
                    ->label('Praktikan')
                    ->placeholder('Pilih praktikan yang hadir pada asistensi')
                    ->required()
                    ->disabled(function ($get) {
                        return $get('schedule_id') ? false : true;
                    })
                    ->options(function ($get) {
                        $shd_id = $get('schedule_id');

                        if (!$shd_id) return [];

                        $attendances    =   [];

                        foreach (Attendance::where('schedule_id', $shd_id)->where('present', true)->get() as $attendance) {
                            $attendances[$attendance->id]   =   $attendance->praktikan->name;
                        }

                        return $attendances;
                    }),

                Forms\Components\TextInput::make('score')
                    ->label('Nilai')
                    ->required()
                    ->numeric()
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('comments')
                    ->label('Komentar/Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.date')
                    ->label('Jadwal Asistensi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendance.praktikan.name')
                    ->label('Praktikan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendance.praktikan.praktikan.kelas')
                    ->label('Kelas Praktikan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendance.praktikan.praktikan.jurusan')
                    ->label('Jurusan Praktikan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('schedule.topic')
                    ->label('Modul Asistensi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('Nilai')
                    ->numeric()
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
        ];
    }
}
