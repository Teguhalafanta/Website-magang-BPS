<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Mahasiswa;

class AbsensiController extends Controller
{
    /**
     * Tampilkan daftar absensi dengan relasi ke mahasiswa
     */
    public function index()
    {
        $absensis = Absensi::with('mahasiswa')->paginate(10);
        $mahasiswas = Mahasiswa::all();

        return view('absensi.index', compact('absensis', 'mahasiswas'));
    }

    /**
     * Simpan data absensi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        Absensi::create($validated);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit absensi
     */
    public function edit($id)
    {
        $absen = Absensi::findOrFail($id);
        $mahasiswas = Mahasiswa::all();

        return view('absensi.edit', compact('absen', 'mahasiswas'));
    }

    /**
     * Update data absensi
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update($validated);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui');
    }

    /**
     * Hapus data absensi
     */
    public function destroy($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus');
    }
}
