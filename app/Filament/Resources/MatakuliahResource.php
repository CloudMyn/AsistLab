<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatakuliahResource\Pages;
use App\Filament\Resources\MatakuliahResource\RelationManagers;
use App\Models\Frekuensi;
use App\Models\MataKuliah;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatakuliahResource extends Resource
{
    protected static ?string $model = Matakuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';

    protected static ?int $navigationSort = -2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->columnSpanFull()
                    ->unique('mata_kuliahs', 'nama', ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('semester')
                    ->required()
                    ->options([
                        'GASAL' => 'Gasal',
                        'GENAP' => 'Genap',
                    ]),

                Forms\Components\TextInput::make('tahun_akademik')
                    ->required()
                    ->columnSpanFull()
                    ->numeric()
                    ->maxLength(255),

                Forms\Components\Repeater::make('frekuensis')
                    ->label('Daftar Frekuensi')
                    ->columnSpanFull()
                    ->distinct()
                    ->createItemButtonLabel('Tambah Frekuensi')
                    ->deleteAction(
                        function (Action $action) {
                            return $action
                                ->before(function ($component, $get, $state, $arguments) {

                                    $id_frekuensi = $state[$arguments['item']]['id'];

                                    $frekuensi = Frekuensi::find($id_frekuensi);

                                    $frekuensi->update([
                                        'mata_kuliah_id'  =>  null
                                    ]);
                                });
                        }
                    )
                    ->simple(Forms\Components\Select::make('id')->placeholder('Pilih Frekuensi')
                        ->distinct()
                        ->options(function ($record) {
                            if (!$record) {
                                return Frekuensi::where('mata_kuliah_id', null)->get()->pluck('name', 'id');
                            }

                            return Frekuensi::get()->pluck('name', 'id');
                        }))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('frekuensi_count')
                    ->default(function ($record) {
                        return $record->frekuensi()->count();
                    })
                    ->badge()
                    ->label('Frekuensi'),

                Tables\Columns\TextColumn::make('semester')
                    ->searchable()
                    ->placeholder('Belum Dipilih'),

                Tables\Columns\TextColumn::make('tahun_akademik')
                    ->searchable()
                    ->placeholder('Tidak Ada'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

                Filter::make('semester')
                    ->label('Featured')
                    ->form([
                        Forms\Components\Select::make('semester')
                            ->label('Semester')
                            ->default('all')
                            ->options([
                                'all'      =>  'Semua',
                                'Gasal'    =>  'Gasal',
                                'Genap'    =>  'Genap',
                            ])
                    ])->query(function (Builder $query, array $data): Builder {
                        $semester = $data['semester'];

                        if ($semester == 'all') {
                            return $query;
                        }

                        if ($semester == 'Gasal') {
                            $query->where('semester', '=', 'Gasal');
                        } else if ($semester == 'Genap') {
                            $query->where('semester', '=', 'Genap');
                        }

                        return $query;
                    }),

                Filter::make('tahun')
                    ->label('Tahun')
                    ->form([
                        Forms\Components\TextInput::make('tahun')
                            ->label('Tahun')
                            ->numeric(),
                    ])->query(function (Builder $query, array $data): Builder {
                        $tahun = $data['tahun'];

                        if (!$tahun) {
                            return $query;
                        }

                        return $query->where('tahun_akademik', '=', $tahun);
                    }),
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
            'index' => Pages\ListMatakuliahs::route('/'),
            'create' => Pages\CreateMatakuliah::route('/create'),
            'edit' => Pages\EditMatakuliah::route('/{record}/edit'),
        ];
    }
}
