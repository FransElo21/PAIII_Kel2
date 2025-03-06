<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function landingpage() {
        return view('welcome'); 
    }

    public function showLoginForm() {
        return view('login'); 
    }

    public function showRegisterForm() {
        return view('register'); 
    }
    
}
