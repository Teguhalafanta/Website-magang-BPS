<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembimbing;

class PembimbingProfileController extends Controller
{
    /**
     * Tampilkan form lengkapi profil pembimbing
     */
    public function create()
    {
        $user = Auth::user();

        // Jika sudah punya profil pembimbing, langsung ke dashboard
        if ($user->pembimbing) {
            return redirect()->route('pembimbing.dashboard');
        }

        return view('pembimbing.profile.create');
    }

    /**
     * Simpan data pembimbing pertama kali
     */
    public function store(Request $request)
    {
        // Cegah data dobel
        if (Auth::user()->pembimbing) {
            return redirect()->route('pembimbing.dashboard');
        }

        // Validasi input
        $request->validate([
            'nip'     => 'nullable|string|max:50|unique:pembimbings,nip',
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'tim'     => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:30',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Proses upload foto (jika ada)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pembimbing', 'public');
        }

        // Simpan ke database
        Pembimbing::create([
            'user_id' => Auth::id(),
            'nip'     => $request->nip,
            'nama'    => $request->nama,
            'jabatan' => $request->jabatan,
            'tim'     => $request->tim,
            'no_telp' => $request->no_telp,
            'email'   => Auth::user()->email,
            'foto'    => $fotoPath,
        ]);

        return redirect()
            ->route('pembimbing.dashboard')
            ->with('success', 'Profil pembimbing berhasil disimpan');
    }
}
