<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // Menampilkan daftar semua mahasiswa
    public function index()
    {
        $mahasiswas = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    // Menampilkan form untuk menambah mahasiswa
    public function create()
    {
        return view('mahasiswa.create');
    }

    // Menyimpan data mahasiswa baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'nim'     => 'required|string|min:10|max:15|unique:mahasiswas,nim',
            'telepon' => 'required|string|min:10|max:13',
            'alamat'  => 'required|string',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    // Menampilkan form edit mahasiswa
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // Menyimpan perubahan data mahasiswa
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'nim'     => 'required|string|min:10|max:15|unique:mahasiswas,nim,' . $mahasiswa->id,
            'telepon' => 'required|string|min:10|max:13',
            'alamat'  => 'required|string',
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    // Menghapus data mahasiswa
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus');
    }
}
