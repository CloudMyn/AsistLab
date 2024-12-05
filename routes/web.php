<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $domains = [
        'asisten' => env('ASISTEN_DOMAIN', null),
        'admin' => env('ADMIN_DOMAIN', null),
        'kepala_lab' => env('KEPALA_LAB_DOMAIN', null),
        'praktikan' => env('PRAKTIKAN_DOMAIN', null),
        'developer' => env('DEVELOPER_DOMAIN', null),
    ];

    if (array_key_exists(request()->getHost(), $domains)) {
        if (auth()->guest()) {
            return redirect()->intended("/{$domains[request()->getHost()]}/login");
        }
    }

    $user   =   get_auth_user();

    return match ($user->roles()->first()->name) {
        'developer' => redirect()->route('filament.developer.pages.dashboard'),
        'admin' => redirect()->route('filament.admin.pages.dashboard'),
        'praktikan' => redirect()->route('filament.praktikan.pages.dashboard'),
        'asisten' => redirect()->route('filament.asisten.pages.dashboard'),
        'kepala_lab' => redirect()->route('filament.kepala_lab.pages.dashboard'),
        default => redirect()->route('filament.admin.pages.dashboard'),
    };
});
