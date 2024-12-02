<?php

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

function get_auth_user(): User
{
    return Auth::user();
}


function send_success_notification($title, $message, $users)
{
    Notification::make('success')
        ->title($title)
        ->body($message)
        ->sendToDatabase($users)
        ->color('success')
        ->icon('heroicon-o-check-circle')
        ->success();
}

function send_warning_notification($title, $message, $users)
{
    Notification::make('warning')
        ->title($title)
        ->body($message)
        ->sendToDatabase($users)
        ->color('warning')
        ->icon('heroicon-o-exclamation-circle')
        ->warning();
}

function send_info_notification($title, $message, $users)
{
    Notification::make('info')
        ->title($title)
        ->body($message)
        ->sendToDatabase($users)
        ->color('info')
        ->icon('heroicon-o-information-circle')
        ->info();
}

function send_danger_notification($title, $message, $users)
{
    Notification::make('danger')
        ->title($title)
        ->body($message)
        ->sendToDatabase($users)
        ->color('danger')
        ->icon('heroicon-o-exclamation')
        ->danger();
}

