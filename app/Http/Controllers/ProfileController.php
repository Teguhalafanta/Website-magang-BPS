<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil mahasiswa magang
     */
    public function show()
    {
        // ambil user login beserta data pelajarnya
        $user = User::with('pelajar')->findOrFail(Auth::id());

        return view('profile.show', compact('user'));
    }

    /**
     * Update data pribadi
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'phone'  => 'nullable|string|max:20',
        ]);

        $user = User::with('pelajar')->findOrFail(Auth::id());

        // update field pada tabel users
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // update field pada tabel pelajars
        if ($user->pelajar) {
            $user->pelajar->update([
                'telepon' => $request->phone,
            ]);
        }

        return back()->with('success', 'Data pribadi berhasil diperbarui.');
    }

    /**
     * Update informasi magang
     */
    public function updateMagang(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'division'   => 'nullable|string|max:100',
            'mentor'     => 'nullable|string|max:100',
            'status'     => 'required|in:aktif,tidak',
        ]);

        $user = User::with('pelajar')->findOrFail(Auth::id());

        if ($user->pelajar) {
            $user->pelajar->update([
                'rencana_mulai'   => $request->start_date,
                'rencana_selesai' => $request->end_date,
                'division'        => $request->division,
                'mentor'          => $request->mentor,
                'status'          => $request->status,
            ]);
        }

        return back()->with('success', 'Informasi magang berhasil diperbarui.');
    }

    /**
     * Update foto profil
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::with('pelajar')->findOrFail(Auth::id());

        if ($user->pelajar) {
            $path = $request->file('photo')->store('foto_mahasiswa', 'public');
            $user->pelajar->update([
                'foto' => $path,
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
