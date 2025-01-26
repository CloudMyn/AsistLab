<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrekuensiResource\Pages;
use App\Filament\Resources\FrekuensiResource\RelationManagers;
use App\Models\Frekuensi;
use App\Models\MataKuliah;
use App\Models\Praktikan;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FrekuensiResource extends Resource
{
    protected static ?string $model = Frekuensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = -1;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->unique('frekuensis', 'name', ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('mata_kuliah_id')
                    ->label('Mata Kuliah')
                    ->options(MataKuliah::get()->pluck('nama', 'id')),

                Forms\Components\Repeater::make('praktikans')
                    ->label('Daftar Praktikan')
                    ->columnSpanFull()
                    ->distinct()
                    ->createItemButtonLabel('Tambah Praktikan')
                    ->deleteAction(
                        function (Action $action) {
                            return $action
                                ->before(function ($component, $get, $state, $arguments) {

                                    $id_praktikan = $state[$arguments['item']]['id'];

                                    $praktikan = Praktikan::find($id_praktikan);

                                    $praktikan->update([
                                        'frekuensi_id'  =>  null
                                    ]);
                                });
                        }
                    )
                    ->simple(Forms\Components\Select::make('id')->placeholder('Pilih Praktikan')->distinct()->options(Praktikan::get()->pluck('user.name', 'id')))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mata_kuliah.nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('praktikan_count')
                    ->default(function ($record) {
                        return $record->praktikans()->count();
                    })
                    ->badge()
                    ->label('Praktikan'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function ($record, $data) {

                        $frekuensi = $record;

                        $frekuensi->update([
                            'name'  =>  $data['name'],
                            'mata_kuliah_id'    =>  $data['mata_kuliah_id']
                        ]);

                        $praktikans =   $data['praktikans'];

                        foreach ($praktikans as $praktikan_id) {

                            $praktikan = Praktikan::find($praktikan_id);

                            $praktikan->update([
                                'frekuensi_id'  =>  $frekuensi->id
                            ]);
                        }

                        Notification::make()
                            ->title('Berhasil')
                            ->success()
                            ->body(ucwords('Frekuensi ' . $frekuensi->name . ' berhasil diperbarui'))
                            ->send();
                    })
                    ->fillForm(function ($record, $data) {
                        $_r = $record->praktikans()->with('user')->get()->pluck('user.name', 'id')->toArray();

                        $praktikans = [];

                        foreach ($_r as $key => $value) {
                            $praktikans[] = $key;
                        }

                        $data = [
                            'name'              =>  $record->name,
                            'mata_kuliah_id'    =>  $record->mata_kuliah_id,
                            'praktikans'        =>  $praktikans,
                        ];

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFrekuensis::route('/'),
        ];
    }
}
