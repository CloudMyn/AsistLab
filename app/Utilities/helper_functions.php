<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

function get_auth_user(): User
{
    return Auth::user();
}
