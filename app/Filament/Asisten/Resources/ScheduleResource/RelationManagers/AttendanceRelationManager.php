<?php

namespace App\Filament\Asisten\Resources\ScheduleResource\RelationManagers;

use App\Filament\Praktikan\Resources\ScheduleResource;
use App\Infolists\Components\ChatBubbleLeft;
use App\Infolists\Components\ChatBubbleRight;
use App\Models\Attendance;
use App\Models\ChatMessage;
use App\Models\RoomChat;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\On;

class AttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'Kehadiran Praktikan';

    protected static ?string $modelLabel = 'Kehadiran Praktikan';

    // protected $listeners = ['triggerRelationMethod' => 'triggerOpenModal'];

    public function mount(): void
    {
        parent::mount();

        // Cek apakah ada parameter praktikan_id
        $praktikanId = session()->get('chatbox_praktikan_id');

        // dump(session());

        // if ($praktikanId) {
        //     // $this->mountTableAction('chat', 2);
        // }
    }


    public function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Praktikan')
                    ->placeholder('Pilih Praktikan')
                    ->columnSpanFull()
                    ->options(function (RelationManager $livewire) {
                        $scheduleId = $livewire->getOwnerRecord()->id;

                        return User::where('peran', 'PRAKTIKAN')->whereDoesntHave('attendances', function ($query) use ($scheduleId) {
                            $query->where('schedule_id', $scheduleId);
                        })->get()->pluck('name', 'id')->toArray();
                    })
                    ->distinct()
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('present')
            ->persistColumnSearchesInSession()
            ->persistFiltersInSession()
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('praktikan.name')
                    ->label('Nama Praktikan')
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('present')
                    ->label('Kehadiran')
                    ->afterStateUpdated(function ($record, $state) {

                        Notification::make()
                            ->title('Berhasil')
                            ->body(ucwords('Kehadiran ' . $record->praktikan->name . ' berhasil diubah'))
                            ->success()
                            ->color('success')
                            ->send();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah')
            ])
            ->actions([
                self::asistenChatBox(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function asistenChatBox()
    {

        return  Tables\Actions\Action::make('chat')
            ->label('Chat')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('info')
            ->slideOver()
            ->modalHeading(function ($record) {
                return ucwords('Chat ' . $record->praktikan->name);
            })
            ->modalSubmitAction(false)
            ->extraModalFooterActions([
                Tables\Actions\Action::make('Kirim Pesan')
                    ->label('Kirim Pesan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->form([
                        Forms\Components\RichEditor::make('message')
                            ->label('Pesan')
                            ->required()
                            ->minLength(3),
                    ])
                    ->action(function (RelationManager $livewire, $record, array $data) {

                        $schedule = $livewire->getOwnerRecord();

                        $asisten_id     =   get_auth_user()->id;
                        $praktikan_id   =   $record->user_id;
                        $schedule_id    =   $schedule->id;

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
                            ->color('info')
                            ->actions([
                                Action::make('Buka Chat')
                                    ->url(ScheduleResource::getUrl('index', isAbsolute: false)),
                            ])
                            ->sendToDatabase($record->praktikan);

                        return $message;
                    }),

                Tables\Actions\Action::make('Hapus Chat')
                    ->label('Tutup Chat')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (RelationManager $livewire, $record) {

                        $schedule = $livewire->getOwnerRecord();

                        $asisten_id     =   get_auth_user()->id;
                        $praktikan_id   =   $record->user_id;
                        $schedule_id    =   $schedule->id;

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
            ->infolist(function (RelationManager $livewire, $record) {

                $schedule = $livewire->getOwnerRecord();

                $asisten_id     =   get_auth_user()->id;
                $praktikan_id   =   $record->user_id;
                $schedule_id    =   $schedule->id;

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
                        $messages[] = ChatBubbleLeft::make(uniqid())
                            ->hiddenLabel(true)
                            ->state([
                                'text' => $message->message,
                                'date' => $message->created_at
                            ]);
                    } else {
                        $messages[] = ChatBubbleRight::make(uniqid())
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
            });
    }

    /**
     * Livewire listeners untuk menangkap event tertentu.
     */
    protected $listeners = [
        'refreshComments' => 'refreshCommentsTable',
    ];

    /**
     * Method yang dipanggil ketika event 'refreshComments' diterima.
     */
    public function refreshCommentsTable()
    {
        // Misalnya, reload tabel agar data terbaru muncul.
        // Di Filament v3, Anda bisa memanggil:
        dd('aaa');
    }

    public function triggerOpenModal($recordId)
    {
        // Memanggil aksi 'openModal' pada baris data dengan ID = $recordId
        $this->callTableColumnAction('chat', $recordId);
    }
}
