<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->guest()) {
        return redirect()->intended('/praktikan/login');
    }

    $user   =   get_auth_user();

    return match($user->roles()->first()->name) {
        'developer' => redirect()->route('filament.developer.pages.dashboard'),
        'admin' => redirect()->route('filament.admin.pages.dashboard'),
        'praktikan' => redirect()->route('filament.praktikan.pages.dashboard'),
        'asisten' => redirect()->route('filament.asisten.pages.dashboard'),
        'kepala_lab' => redirect()->route('filament.kepala_lab.pages.dashboard'),
        default => redirect()->route('filament.praktikan.pages.dashboard'),
});
