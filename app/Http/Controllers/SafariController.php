<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SafariController extends Controller
{
    public function index(){
        return view('tours.index');
    }
}
