<?php

namespace App\Filament\KepalaLab\Resources;

use App\Filament\KepalaLab\Resources\AssessmentScheduleResource\Pages;
use App\Filament\KepalaLab\Resources\AssessmentScheduleResource\RelationManagers;
use App\Models\AssessmentSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessmentScheduleResource extends Resource
{
    protected static ?string $model = AssessmentSchedule::class;

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
                Forms\Components\TextInput::make('schedule_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('mata_kuliah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('frekuensi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jadwal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('asisten')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('asisten_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('topik')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nilai')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('approver_id')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('approved_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mata_kuliah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('frekuensi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jadwal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('topik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asisten')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Disetujui Pada')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Belum Disetujui')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('export_pdf')
                    ->label('Laporan')
                    ->color('warning')
                    ->url(function ($record) {
                        return route('pdf-export-penilaian', [$record->id]);
                    }, true)
                    ->icon('heroicon-o-arrow-down-tray'),

                Tables\Actions\Action::make('setujui')
                    ->label('Setujui')
                    ->color('success')
                    ->disabled(function ($record) {
                        return $record->approved_at !== null;
                    })
                    ->icon('heroicon-s-check-circle')
                    ->action(function ($record) {
                        $record->update([
                            'approved_at'   =>   now(),
                            'approver_id'   =>   get_auth_user()->id,
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Penilaian Asistensi Berhasil Disetujui')
                            ->send();
                    }),
            ])
            ->defaultSort('approved_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAssessmentSchedules::route('/'),
        ];
    }
}
