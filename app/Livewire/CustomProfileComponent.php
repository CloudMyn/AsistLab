<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class CustomProfileComponent extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $record;

    public function mount(): void
    {
        $this->form->fill($this->record?->attributesToArray() ?? []);
    }

    public static function getSort(): ?int
    {
        return 1;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->prefix('+62')
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(['ACTIVE' => 'Aktif', 'NONACTIVE' => 'Tidak Aktif', 'BLOCKED' => 'Diblokir'])
                    ->required(),

            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
    }

    public function render(): View
    {
        return view('livewire.custom-profile-component');
    }

    public function submitAction(): void
    {
        // $this->save();
    }
}
