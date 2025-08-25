<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register_pelajar');
    }

    // Menyimpan data mahasiswa
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:users,nim',
            'nama' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'asal_univ' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'email' => 'required|string|email:dns|unique:users,email',
            'password' => 'required|string|min:5'
        ]);

        User::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'username' => $request->username,
            'asal_univ' => $request->asal_univ,
            'jurusan' => $request->jurusan,
            'prodi' => $request->prodi,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return to_route('login');
    }
}
