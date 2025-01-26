<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\AssessmentResource\Pages;
use App\Filament\Dosen\Resources\AssessmentResource\RelationManagers;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Schedule;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query()->where('approved_at', '!=', null);

        if (
            static::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            static::scopeEloquentQueryToTenant($query, $tenant);
        }

        return $query;
    }

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

    public static function canDeleteAny(): bool
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('matakuliah_name')
                    ->label('Mata Kuliah')
                    ->required(),

                Forms\Components\TextInput::make('frekuensi_name')
                    ->label('Frekuensi')
                    ->required(),

                Forms\Components\TextInput::make('schedule_topic')
                    ->label('Jadwal')
                    ->required(),

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
                Tables\Columns\TextColumn::make('schedule.mata_kuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('attendance.praktikan.praktikan.frekuensi.name')
                    ->label('Frekuensi')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('schedule.date')
                    ->label('Jadwal Asistensi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('attendance.praktikan.name')
                    ->label('Praktikan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('schedule.topic')
                    ->label('Topik')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('attendance.schedule.asisten.name')
                    ->label('Asisten')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('attendance.praktikan.praktikan.kelas')
                    ->label('Kelas Praktikan')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('attendance.praktikan.praktikan.jurusan')
                    ->label('Jurusan Praktikan')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                Tables\Actions\ViewAction::make()
                    ->fillForm(function ($record) {
                        $data = $record->toArray();

                        $data['schedule_topic']    =   $record->schedule->topic;
                        $data['frekuensi_name']    =   $record->attendance->praktikan->praktikan->frekuensi->name;
                        $data['matakuliah_name']   =   $record->schedule?->mata_kuliah?->nama;
                        $data['comments']          =   $record->comments ?? 'Tidak ada catatan';

                        return $data;
                    }),
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
