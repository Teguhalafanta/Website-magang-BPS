<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Tampilkan halaman profil
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    // Update data pribadi (tabel pelajars)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:pelajars,email',
            'phone'  => 'nullable|string|max:20',
        ]);

        $user->pelajar->update([
            'nama'    => $request->name,
            'email'   => $request->email,
            'telepon' => $request->phone,
        ]);

        return redirect()->route('profile.show')->with('success', 'Data pribadi berhasil diperbarui!');
    }

    // Update informasi magang (tabel pelajars)
    public function updateMagang(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'division'   => 'nullable|string|max:100',
            'mentor'     => 'nullable|string|max:100',
            'status'     => 'required|in:aktif,tidak',
        ]);

        $user->pelajar->update([
            'rencana_mulai'   => $request->start_date,
            'rencana_selesai' => $request->end_date,
            'division'        => $request->division,
            'mentor'          => $request->mentor,
            'status'          => $request->status,
        ]);

        return redirect()->route('profile.show')->with('success', 'Informasi magang berhasil diperbarui!');
    }

    // Update foto profil (tabel pelajars)
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('photo')->store('avatars', 'public');

        $user->pelajar->update([
            'foto' => $path,
        ]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui!');
    }
}
