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
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('app.navigation.user_table');
    }

    public static function getNavigationGroup(): ?string
    {
        // return __('app.navigation.user_management');
        return null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('peran', '!=', 'DEVELOPER');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar_url')
                    ->label('Avatar')
                    ->image()
                    ->columnSpanFull()
                    ->directory('avatar'),

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

                Forms\Components\Select::make('peran')
                    ->label('Peran Pengguna')
                    ->placeholder('Pilih Peran Pengguna')
                    ->live()
                    ->options([
                        'ADMIN' => 'Admin',
                        'ASISTEN' => 'Asisten',
                        'PRAKTIKAN' => 'Praktikan',
                        'KEPALA_LAB_DAN_DOSEN' => 'Kepala Lab & Dosen',
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->columnSpanFull()
                    ->options(['ACTIVE' => 'Aktif', 'NONACTIVE' => 'Tidak Aktif', 'BLOCKED' => 'Diblokir'])
                    ->required(),

                Forms\Components\Fieldset::make('Password')
                    ->columnSpanFull()
                    ->label('Kata Sandi')
                    ->visible(function ($get) {
                        return $get('peran') == 'PRAKTIKAN';
                    })
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


                Forms\Components\Fieldset::make('praktikan')
                    ->columnSpanFull()
                    ->label('Data Praktikan')
                    ->relationship('praktikan')
                    ->schema([

                        Forms\Components\TextInput::make('kelas')
                            ->label('Kelas')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jurusan')
                            ->label('Jurusan')
                            ->required()
                            ->maxLength(255),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->placeholder('Tidak Ada Gambar')
                    ->defaultImageUrl('/default_pp.png'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->prefix('@')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Alamat Email')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

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
            ->defaultSort('created_at', 'desc')
            ->filtersFormWidth('lg')
            ->filters([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
