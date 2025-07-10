<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.store-settings.edit');
    }
}
