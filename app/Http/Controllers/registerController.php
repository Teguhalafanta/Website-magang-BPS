<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Tambahkan ini jika belum ada

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register_pelajar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:5',
            'nim'      => 'required|string|unique:mahasiswas,nim',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'nim'      => $request->nim,
        ]);

        Mahasiswa::create([
            'nama'      => $request->nama,
            'nim'       => $request->nim,
            'asal_univ' => $request->asal_univ,
            'jurusan'   => $request->jurusan,
            'prodi'     => $request->prodi,
            'user_id'   => $user->id,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }
}
