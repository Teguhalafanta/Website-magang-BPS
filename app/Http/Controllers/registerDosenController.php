<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class RegisterDosenController extends Controller
{
    public function index()
    {
        return view('auth.register_dosen');
    }

    // Simpan data dosen baru
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:dosens,nip',
            'nama' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:dosens,username',
            'email' => 'required|string|email:dns|unique:dosens,email',
            'password' => 'required|string|min:5'
        ]);

        Dosen::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // setelah daftar, redirect ke halaman login Dosen
        return redirect()->route('login.dosen')->with('success', 'Pendaftaran dosen berhasil, silakan login.');
    }
}
