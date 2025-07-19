<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function cart()
    {
        return view('client.orders.cart');
    }

    public function checkout()
    {
        return view('client.orders.checkout');
    }
}
