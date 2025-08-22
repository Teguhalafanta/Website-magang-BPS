<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginSSOController extends Controller
{
    public function index()
    {
        return view('auth.login_sso');
    }
}
