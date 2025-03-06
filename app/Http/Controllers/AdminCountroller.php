<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminCountroller extends Controller
{
    public function adminadminpage() {
        return view('admin/index-admin'); 
    }
}
