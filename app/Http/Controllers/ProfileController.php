<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Menampilkan profil user yang sedang login
    public function profile()
    {
        $profile = Auth::user(); // ambil user yang login
        return view('mahasiswa.profile', compact('profile'));
    }

    // Update profil user
    public function update(Request $request)
    {
        $profile = Auth::user();

        dd(get_class($profile)); // cek class instance sebenarnya
    }
}
