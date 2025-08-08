<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function editProfile()
    {
        return view('client.users.profile-edit');
    }

    public function changePassword()
    {
        return view('client.users.password-edit');
    }

    public function changeAppearance()
    {
        return view('client.users.appearance-edit');
    }
}
