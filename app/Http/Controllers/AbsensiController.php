<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Mahasiswa;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::with('mahasiswa')->paginate(10);
        $mahasiswas = Mahasiswa::all();
        return view('absensi.index', compact('absensis', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        Absensi::create($request->all());

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $absen = Absensi::findOrFail($id);
        $mahasiswas = Mahasiswa::all();
        return view('absensi.edit', compact('absen', 'mahasiswas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id', // perbaikan di sini juga
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update($request->all());

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diupdate');
    }

    public function destroy($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus');
    }
}
