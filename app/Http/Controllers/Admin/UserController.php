<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit($id)
    {
        return view('admin.users.edit', compact('id'));
    }

    public function editProfile()
    {
        return view('admin.users.profile-edit');
    }

    public function changePassword()
    {
        return view('admin.users.password-edit');
    }

    public function changeAppearance()
    {
        return view('admin.users.appearance-edit');
    }
}
