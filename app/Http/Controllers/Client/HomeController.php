<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\StoreSettingsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.index');
    }
}
