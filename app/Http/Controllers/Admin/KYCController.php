<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KYCController extends Controller
{
    public function index()
    {
        return view('admin.kyc.index');
    }

    public function show($id)
    {
        return view('admin.kyc.show', compact('id'));
    }
}
