<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Tampilkan halaman profil
    public function show()
    {
        $user = User::with('pelajar')->findOrFail(Auth::id());
        return view('profile.show', compact('user'));
    }

    // Update data pribadi
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email',
            'telepon' => 'nullable|string|max:20',
        ]);

        // Update ke tabel users
        $user->update([
            'name'  => $validated['nama'],
            'email' => $validated['email'],
            'phone' => $validated['telepon'],
        ]);

        // Update atau buat data di tabel pelajars
        $user->pelajar()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Data pribadi berhasil diperbarui');
    }

    // Update informasi magang (khusus pelajar)
    public function updateMagang(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'rencana_mulai' => 'nullable|date',
            'rencana_selesai' => 'nullable|date',
            'mentor' => 'nullable|string|max:255',
            'status' => 'required|string|in:aktif,tidak',
        ]);

        $user->pelajar()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Informasi magang berhasil diperbarui');
    }

    // Update foto profil (tabel users)
    public function updateFoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $modelUser = $user instanceof \Illuminate\Database\Eloquent\Model
            ? $user
            : User::findOrFail($user->id ?? $user->id_user);

        // hapus foto lama kalau ada
        if ($modelUser->foto && Storage::disk('public')->exists($modelUser->foto)) {
            Storage::disk('public')->delete($modelUser->foto);
        }

        $path = $request->file('foto')->store('avatars', 'public');

        $modelUser->update(['foto' => $path]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui!');
    }
}
