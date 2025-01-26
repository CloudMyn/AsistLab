<?php

namespace App\Filament\Asisten\Resources;

use App\Filament\Asisten\Resources\ScheduleResource\Pages;
use App\Filament\Asisten\Resources\ScheduleResource\RelationManagers\AttendanceRelationManager;
use App\Models\Frekuensi;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

/**
 * Schedule Resource
 *
 * Resource ini digunakan untuk mengatur data Jadwal Asistensi.
 * Resource ini di tampilkan pada menu "Jadwal Asistensi" di dashboard asistensi.
 */
class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?int $navigationSort = 2;

    /**
     * Returns the label for the model.
     *
     * @return string
     */
    public static function getModelLabel(): string
    {
        return "Jadwal Asistensi";
    }

    /**
     * The navigation group for this resource.
     *
     * @return string|null
     */
    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    /**
     * Returns the eloquent query for this resource.
     *
     * Includes the {@see \App\Models\User} relationship.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', get_auth_user()->id);
    }
    /**
     * Konfigurasi skema form untuk sumber daya Jadwal.
     *
     * Form ini mencakup input field untuk topik, ruangan, dan status jadwal.
     * Juga termasuk fieldset untuk memilih tanggal dan waktu jadwal.
     * Selain itu, ia juga mencakup repeater untuk mengelola kehadiran jadwal,
     * memungkinkan pemilihan pengguna dengan peran 'PRAKTIKAN'.
     *
     * @param Form $form Contoh form untuk dikonfigurasi.
     * @return Form Contoh form yang dikonfigurasi.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([

                Forms\Components\Select::make('mata_kuliah_id')
                    ->required()
                    ->relationship('mata_kuliah', 'nama')
                    ->live()
                    ->label('Mata Kuliah'),

                Forms\Components\TextInput::make('topic')
                    ->label('Modul')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),

                Forms\Components\Select::make('room')
                    ->label('Kelas/Ruangan')
                    ->required()
                    ->options([
                        "Startup" => "Startup",
                        "IoT" => "IoT",
                        "Computer Network" => "Computer Network",
                        "Computer Vision" => "Computer Vision",
                        "Multimedia" => "Multimedia",
                        "Data Science" => "Data Science",
                        "Microcontroller" => "Microcontroller"
                    ]),


                Forms\Components\Select::make('status')
                    ->label('Status Asistensi')
                    ->columnSpanFull()
                    ->required()
                    ->options([
                        'SCHEDULED' => 'Terjadwal',
                        'CANCELLED' => 'Dibatalkan',
                        'COMPLETED' => 'Selesai',
                    ]),

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
                            ->step(60)
                            ->required(),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->minDate(today())
                            ->step(60)
                            ->required(),
                    ]),

                Select::make('frekuensi')
                    ->label('Frekuensi')
                    ->live()
                    ->columnSpanFull()
                    ->hiddenOn('edit')
                    ->preload()
                    ->disabled(function ($get) {
                        return $get('mata_kuliah_id') == null;
                    })
                    ->options(function ($get) {
                        $mata_kuliah_id = $get('mata_kuliah_id');

                        if (!$mata_kuliah_id) return [];

                        return Frekuensi::where('mata_kuliah_id', $mata_kuliah_id)->pluck('name', 'id');
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $frekuensi = Frekuensi::find($state);

                        if (!$frekuensi) return;

                        $praktikans = [];

                        foreach ($frekuensi->praktikans as $praktikan) {
                            $praktikans[] = [
                                'user_id' => $praktikan->user->name,
                            ];
                        }

                        $set('attendances', $praktikans);
                    })
                    ->required(),

                Repeater::make('attendances')
                    ->label('Daftar Praktikan')
                    ->addable(false)
                    ->columnSpanFull()
                    ->hiddenOn('edit')
                    ->disabled()
                    ->simple(
                        Forms\Components\TextInput::make('user_id')
                            ->label('Praktikan'),
                    ),
            ]);
    }

    /**
     * Mendefinisikan kolom tabel yang akan digunakan dalam tampilan daftar.
     *
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // definisikan kolom tanggal
                Tables\Columns\TextColumn::make('date')
                    ->label('Jadwal Asistensi')
                    ->description(function ($record) {
                        return $record->start_time . " - " . $record->end_time;
                    })
                    ->date('d F Y')
                    ->sortable(),

                // definisikan kolom topik
                Tables\Columns\TextColumn::make('topic')
                    ->label('Topik')
                    ->searchable(),

                // definisikan kolom topik
                Tables\Columns\TextColumn::make('mata_kuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                // definisikan kolom room
                Tables\Columns\TextColumn::make('room')
                    ->label('Ruangan')
                    ->searchable(),

                // definisikan kolom status
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'SCHEDULED' => 'Terjadwal',
                        'CANCELLED' => 'Dibatalkan',
                        'COMPLETED' => 'Selesai',
                    ]),

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
            ->defaultSort('date', 'desc')
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

    /**
     * Get the relation managers that should be available for the resource.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            AttendanceRelationManager::class
        ];
    }

    /**
     * Mendefinisikan halaman yang tersedia untuk sumber daya Jadwal Asistensi.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
