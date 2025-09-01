<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan semua data mahasiswa
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::paginate(10);
        $totalMahasiswa = Mahasiswa::count();

        return view('mahasiswa.index', compact('mahasiswas', 'totalMahasiswa'));
    }

    /**
     * Simpan data mahasiswa baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim_nisn' => 'required|string|max:20|unique:mahasiswas,nim_nisn',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        Mahasiswa::create([
            'nama' => $request->nama,
            'nim_nisn' => $request->nim_nisn,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit mahasiswa
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update data mahasiswa
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nim_nisn'  => 'required|string|max:50|unique:mahasiswas,nim_nisn,' . $mahasiswa->id_pelajar,
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    /**
     * Hapus data mahasiswa
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus');
    }
}
