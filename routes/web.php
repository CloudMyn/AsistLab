<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $domains = [
        'asisten' => env('ASISTEN_DOMAIN', null),
        'admin' => env('ADMIN_DOMAIN', null),
        'kepala-lab' => env('KEPALA_LAB_DOMAIN', null),
        'praktikan' => env('PRAKTIKAN_DOMAIN', null),
        'developer' => env('DEVELOPER_DOMAIN', null),
    ];

    $host = request()->getHost();
    $domainKey = array_search($host, $domains);

    dd($domainKey, $host);

    if ($domainKey !== false) {
        return redirect()->intended("/{$domainKey}/login");
    } else {
        return redirect()->route('filament.praktikan.auth.login');
    }
});
