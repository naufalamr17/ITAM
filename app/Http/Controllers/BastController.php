<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BastController extends Controller
{
    public function index()
    {
        return view('pages.bast.index'); // You'll need to create this view
    }
}
