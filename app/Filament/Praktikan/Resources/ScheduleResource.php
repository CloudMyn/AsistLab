<?php

namespace App\Filament\Praktikan\Resources;

use App\Filament\Asisten\Resources\ScheduleResource as ResourcesScheduleResource;
use App\Filament\Asisten\Resources\ScheduleResource\RelationManagers\AttendanceRelationManager;
use App\Filament\Praktikan\Resources\ScheduleResource\Pages;
use App\Filament\Praktikan\Resources\ScheduleResource\RelationManagers;
use App\Infolists\Components\ChatBubble;
use App\Infolists\Components\ChatBubbleLeft;
use App\Infolists\Components\ChatBubbleRight;
use App\Models\ChatMessage;
use App\Models\RoomChat;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
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
                    ->label('Modul'),

                Forms\Components\TextInput::make('mata_kuliah.nama')
                    ->label('Mata Kuliah'),

                Forms\Components\TextInput::make('room')
                    ->label('Kelas/Ruangan'),

                Fieldset::make('Waktu')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->columnSpanFull()
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

                Tables\Columns\TextColumn::make('mata_kuliah.nama')
                    ->label('Mata Kuliag')
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
                Tables\Actions\Action::make('chat')
                    ->label('Chat')
                    ->modalHeading(function ($record) {
                        return ucwords('Chat ' . $record->asisten->name);
                    })
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->slideOver()
                    ->modalFooterActions([])
                    ->mountUsing(function (Table $table) {
                        $table->poll('2s');
                    })
                    ->modalSubmitAction(false)
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('Kirim Pesan')
                            ->label('Kirim Pesan')
                            ->icon('heroicon-o-paper-airplane')
                            ->color('success')
                            ->form([
                                RichEditor::make('message')
                                    ->label('Pesan')
                                    ->required()
                                    ->minLength(3),
                            ])
                            ->action(function (Schedule $record, array $data) {

                                $asisten_id     =   $record->user_id;
                                $praktikan_id   =   get_auth_user()->id;
                                $schedule_id    =   $record->id;

                                $room_chat  =   RoomChat::where('schedule_id', $schedule_id)
                                    ->where('user_id_asisten', $asisten_id)
                                    ->where('user_id_praktikan', $praktikan_id)
                                    ->first();

                                $message = ChatMessage::create([
                                    'room_chat_id'   =>  $room_chat->id,
                                    'user_id'        =>  get_auth_user()->id,
                                    'message'        =>  $data['message']
                                ]);

                                Notification::make()
                                    ->title('Pesan Baru')
                                    ->body('Anda mendapatkan pesan baru dari ' . get_auth_user()->name)
                                    ->actions([
                                        Action::make('Buka Chat')
                                            ->url(ResourcesScheduleResource::getUrl('edit', ['record' => $record, 'action' => 'chat', 'praktikan_id' => get_auth_user()->id], isAbsolute: false)),
                                    ])
                                    ->color('info')
                                    ->sendToDatabase($record->asisten);

                                return $message;
                            }),


                        Tables\Actions\Action::make('Hapus Chat')
                            ->label('Tutup Chat')
                            ->icon('heroicon-o-x-circle')
                            ->requiresConfirmation()
                            ->color('danger')
                            ->action(function (Schedule $record) {

                                $asisten_id     =   $record->user_id;
                                $praktikan_id   =   get_auth_user()->id;
                                $schedule_id    =   $record->id;

                                $room_chat  =   RoomChat::where('schedule_id', $schedule_id)
                                    ->where('user_id_asisten', $asisten_id)
                                    ->where('user_id_praktikan', $praktikan_id)
                                    ->first();

                                if ($room_chat) {
                                    $room_chat->delete();

                                    Notification::make()
                                        ->title('Berhasil')
                                        ->body('Chat berhasil dihapus')
                                        ->success()
                                        ->color('success')
                                        ->send();
                                }
                            }),
                    ])
                    ->infolist(function (Schedule $record) {

                        $asisten_id     =   $record->user_id;
                        $praktikan_id   =   get_auth_user()->id;
                        $schedule_id    =   $record->id;

                        $room_chat  =   RoomChat::where('schedule_id', $schedule_id)
                            ->where('user_id_asisten', $asisten_id)
                            ->where('user_id_praktikan', $praktikan_id)
                            ->first();

                        if (!$room_chat) {
                            $room_chat  =   RoomChat::create([
                                'room_code'         =>  \Illuminate\Support\Str::random(16),
                                'schedule_id'       =>  $schedule_id,
                                'user_id_asisten'   =>  $asisten_id,
                                'user_id_praktikan' =>  $praktikan_id
                            ]);
                        };

                        $all_messages       = $room_chat->chat_messages()->orderBy('created_at', 'asc')->get();

                        $messages = [];

                        foreach ($all_messages as $message) {

                            if ($message->user_id == $asisten_id) {
                                $messages[] = ChatBubbleRight::make(uniqid())
                                    ->hiddenLabel(true)
                                    ->state([
                                        'text' => $message->message,
                                        'date' => $message->created_at
                                    ]);
                            } else {
                                $messages[] = ChatBubbleLeft::make(uniqid())
                                    ->hiddenLabel(true)
                                    ->state([
                                        'text' => $message->message,
                                        'date' => $message->created_at
                                    ]);
                            }
                        }

                        if (count($messages) == 0) {
                            $messages[] = TextEntry::make(uniqid())
                                ->hiddenLabel(true)
                                ->state('Belum Ada Pesan');
                        }


                        return $messages;
                    }),
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
