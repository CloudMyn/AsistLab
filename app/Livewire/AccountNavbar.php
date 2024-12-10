<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Infolists\Components\ImageEntry;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AccountNavbar extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function logout(): Action
    {
        return Action::make('profile')
            ->color('info')
            ->label('Edit Profile')
            ->keyBindings(['command+m', 'ctrl+m'])
            ->extraAttributes(['class' => 'w-full'])
            ->url(fn(): string => EditProfilePage::getUrl());
    }

    public function render(): string
    {
        return <<<'HTML'
            <div class="space-y-2">

            <div style="display: flex; align-items: center; justify-content: center; width: 100%; flex-direction: column">
                <img class="fi-avatar object-cover object-center fi-circular rounded-full h-8 w-8 fi-user-avatar" src="{{ get_auth_user()->getFilamentAvatarUrl() }}" alt="Avatar admin" style="width: 120px; height: 120px;">
                <h2 class="fi-user-name mt-2">{{ ucwords(get_auth_user()->name) }}</h2>
                <h4 class="mt-1" style="font-weight: 400; font-size: 14px; color: #6b7280">{{ ucwords(get_auth_user()->peran) }}</h4>
            </div>

            {{ $this->logout }}

            </div>
        HTML;
    }
}
