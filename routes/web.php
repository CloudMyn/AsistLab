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
        return redirect()->intended("/{$domains[request()->getHost()]}/login");
    } else {
        return redirect()->route('filament.praktikan.auth.login');
    }
});
