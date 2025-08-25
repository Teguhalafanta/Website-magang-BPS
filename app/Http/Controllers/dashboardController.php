<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }

    /** profile user */
    public function userProfile()
    {
        return view('dashboard.profile');
    }
}
