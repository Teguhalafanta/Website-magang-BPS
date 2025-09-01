<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan.
     */
    public function index()
    {
        $kegiatans  = Kegiatan::orderBy('created_at', 'desc')->paginate(10);
        $mahasiswa = null;
        $absenHariIni = null;

        if (auth()->check() && auth()->user()->mahasiswa) {
            $mahasiswa = auth()->user()->mahasiswa;
            $absenHariIni = Absensi::where('id_pelajar', $mahasiswa->id_pelajar)
                ->whereDate('tanggal', now()->toDateString())
                ->first();
        }

        return view('kegiatan.index', compact('kegiatans', 'mahasiswa', 'absenHariIni'));
    }


    /**
     * Tampilkan form buat kegiatan (opsional, tidak dipakai kalau langsung di index).
     */
    public function create()
    {
        return redirect()->route('kegiatan.index');
    }

    /**
     * Simpan kegiatan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(Kegiatan $kegiatan)
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update data kegiatan.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus data kegiatan.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
