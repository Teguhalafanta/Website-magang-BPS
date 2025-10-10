<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil mahasiswa magang
     */
    public function show()
    {
        // ambil user dari database lengkap dengan relasi pelajar
        $user = \App\Models\User::with('pelajar')->findOrFail(Auth::id());

        $data = null;

        // jika role pelajar
        if ($user->role === 'pelajar') {
            $data = $user->pelajar;
        }

        // jika pembimbing atau admin
        if (in_array($user->role, ['pembimbing', 'admin'])) {
            $data = $user;
        }

        return view('profile.show', compact('user', 'data'));
    }

    /**
     * Update data pribadi
     */
    public function update(Request $request)
    {
        $user = User::with('pelajar')->findOrFail(Auth::id());

        // Validasi umum (berlaku untuk semua role)
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim_nisn' => 'nullable|string|max:50',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'asal_institusi' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        // Jika user adalah pelajar → update tabel pelajar
        if ($user->role === 'pelajar' && $user->pelajar) {
            $user->pelajar->update([
                'nama' => $request->nama,
                'nim_nisn' => $request->nim_nisn,
                'fakultas' => $request->fakultas,
                'jurusan' => $request->jurusan,
                'asal_institusi' => $request->asal_institusi,
                'telepon' => $request->telepon,
            ]);
        }

        // Jika user adalah admin atau pembimbing → update data langsung di tabel users
        if (in_array($user->role, ['pembimbing', 'admin'])) {
            $user->update([
                'username' => $request->nama, // gunakan field 'username' untuk nama
            ]);
        }

        // Pesan sukses berdasarkan role
        $pesan = match ($user->role) {
            'pelajar' => 'Data pribadi pelajar berhasil diperbarui.',
            'pembimbing' => 'Profil pembimbing berhasil diperbarui.',
            'admin' => 'Profil admin berhasil diperbarui.',
            default => 'Data pribadi berhasil diperbarui.'
        };

        return redirect()->route('profile.show')->with('success', $pesan);
    }

    /**
     * Update informasi magang
     */
    public function updateMagang(Request $request)
    {
        $request->validate([
            'rencana_mulai'   => 'nullable|date',
            'rencana_selesai' => 'nullable|date',
            'mentor'          => 'nullable|string|max:100',
            'status'          => 'required|in:aktif,tidak aktif',
        ]);

        $user = User::with('pelajar')->findOrFail(Auth::id());

        // hanya pelajar yang punya data magang
        if ($user->role == 'pelajar' && $user->pelajar) {
            $user->pelajar->update([
                'rencana_mulai'   => $request->rencana_mulai,
                'rencana_selesai' => $request->rencana_selesai,
                'mentor'          => $request->mentor,
                'status'          => $request->status,
            ]);
        }

        return back()->with('success', 'Informasi magang berhasil diperbarui.');
    }

    /**
     * Update foto profil
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::findOrFail(Auth::id()); // langsung Eloquent model

        // hapus foto lama kalau ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // simpan foto baru
        $path = $request->file('foto')->store('avatars', 'public');

        $user->update(['foto' => $path]);

        return redirect()->route('profile.show')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }
}
