<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }

    public function edit($id)
    {
        return view('users.edit', compact('id'));
    }

    public function editProfile()
    {
        return view('users.profile-edit');
    }

    public function changePassword()
    {
        return view('users.password-edit');
    }

    public function changeAppearance()
    {
        return view('users.appearance-edit');
    }
}
