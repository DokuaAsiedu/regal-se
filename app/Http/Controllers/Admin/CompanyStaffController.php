<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyStaffController extends Controller
{
    public function index()
    {
        return view('admin.company-staff.index');
    }
}
