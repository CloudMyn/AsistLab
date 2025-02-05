<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    // [1] KONFIGURASI MODEL DAN NAVIGASI
    // Menentukan model Eloquent yang terkait dengan resource ini
    protected static ?string $model = User::class;

    // Mengatur ikon yang akan ditampilkan di sidebar navigasi
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // Mengatur urutan tampilan di sidebar navigasi
    protected static ?int $navigationSort = 1;

    // [2] GET MODEL LABEL
    // Memberikan label untuk model yang digunakan dalam tampilan
    public static function getModelLabel(): string
    {
        return __('app.navigation.user_table');
    }

    // [3] GET NAVIGATION GROUP
    // Mengelompokkan resource ini ke dalam grup navigasi tertentu
    public static function getNavigationGroup(): ?string
    {
        // return __('app.navigation.user_management');
        return null;
    }

    // [4] ELOQUENT QUERY
    // Membangun query dasar untuk mengambil data dari database
    // Menambahkan filter untuk mengecualikan pengguna dengan peran 'DEVELOPER'
    public static function getEloquentQuery(): Builder
    {
        return User::query();
    }

    // [5] FORM CONFIGURATION
    // Membuat struktur form untuk create/edit data
    // Mengatur validasi dan tipe input untuk setiap field
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // [5.1] UPLOAD AVATAR
                Forms\Components\FileUpload::make('avatar_url')
                    ->label('Avatar')
                    ->image()
                    ->columnSpanFull()
                    ->directory('avatar'),

                // [5.2] INPUT DATA UTAMA
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->unique('users', 'username', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                // [5.3] PILIHAN PERAN
                Forms\Components\Select::make('peran')
                    ->label('Peran Pengguna')
                    ->placeholder('Pilih Peran Pengguna')
                    ->live()
                    ->options([
                        'ADMIN' => 'Admin',
                        'ASISTEN' => 'Asisten',
                        'PRAKTIKAN' => 'Praktikan',
                        'KEPALA_LAB' => 'Kepala Lab & Dosen',
                    ])
                    ->required(),

                // [5.4] STATUS AKUN
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->columnSpanFull()
                    ->options(['ACTIVE' => 'Aktif', 'NONACTIVE' => 'Tidak Aktif', 'BLOCKED' => 'Diblokir'])
                    ->required(),

                // [5.5] FIELD PASSWORD
                Forms\Components\Fieldset::make('Password')
                    ->columnSpanFull()
                    ->label('Kata Sandi')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->required(function ($record) {
                                return !$record;
                            })
                            ->confirmed()
                            ->revealable()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->revealable(),
                    ]),

                // [5.6] DATA PRAKTIKAN
                Forms\Components\Fieldset::make('praktikan')
                    ->columnSpanFull()
                    ->label('Data Praktikan')
                    ->relationship('praktikan')
                    ->visible(function ($get) {
                        return $get('peran') == 'PRAKTIKAN';
                    })
                    ->schema([
                        Forms\Components\TextInput::make('kelas')
                            ->label('Kelas')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jurusan')
                            ->label('Jurusan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('frekuensi_id')
                            ->label('Frekuensi')
                            ->columnSpanFull()
                            ->relationship('frekuensi', 'name')
                            ->required(),
                    ]),
            ]);
    }

    // [6] TABLE CONFIGURATION
    // Mengonfigurasi tampilan tabel data
    // Menentukan kolom yang ditampilkan dan aksi yang tersedia
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // [6.1] KOLOM GAMBAR
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->placeholder('Tidak Ada Gambar')
                    ->defaultImageUrl('/default_pp.png'),

                // [6.2] KOLOM TEKS UTAMA
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('praktikan.frekuensi.name')
                    ->label('Frekuensi')
                    ->placeholder('Tidak Ada Frekuensi')
                    ->searchable(),

                // [6.3] KOLOM OPSIONAL
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->prefix('@')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Alamat Email')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                // [6.4] BADGE STATUS
                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                // [6.5] KOLOM TANGGAL
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Buat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // [7] DEFAULT SORTING
            ->defaultSort('created_at', 'desc')
            ->filtersFormWidth('lg')
            ->filters([
                // [7.1] FILTER TANGGAL PEMBUATAN
                Filter::make('created_at')
                    ->form([
                        Fieldset::make('Filter Tanggal Pembuatan')
                            ->schema([
                                DatePicker::make('created_from')
                                    ->label('Dari Tanggal'),
                                DatePicker::make('created_until')
                                    ->label('Hingga Tanggal'),
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            // [8] TABLE ACTIONS
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // [9] BULK ACTIONS
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // [10] RESOURCE RELATIONS
    // Mendefinisikan relasi yang terkait dengan resource ini
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // [11] RESOURCE PAGES
    // Mendefinisikan halaman-halaman yang terkait dengan resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
