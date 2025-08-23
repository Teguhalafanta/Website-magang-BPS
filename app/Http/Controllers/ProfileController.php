<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Halaman profil
    public function profile()
    {
        // Ambil data user yang sedang login
        $profile = Auth::user();

        return view('mahasiswa.profile', compact('profile'));
    }

    // Halaman edit profil
    public function edit()
    {
        $profile = Auth::user();
        return view('mahasiswa.edit-profile', compact('profile'));
    }

    // Proses update profil
    public function update(Request $request)
    {
        $profile = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $profile->id,
        ]);

        $profile->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
