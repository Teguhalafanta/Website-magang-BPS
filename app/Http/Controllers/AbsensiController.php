<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Kegiatan;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Tampilkan daftar absensi, baik semua atau hanya hari ini.
     */
    public function index(Request $request)
    {
        // Ambil data absensi (semua atau hanya hari ini)
        if ($request->has('today')) {
            $absensis = Absensi::with('mahasiswa')
                ->whereDate('tanggal', now()->toDateString())
                ->get();
        } else {
            $absensis = Absensi::with('mahasiswa')->paginate(10);
        }

        // Data tambahan untuk tampilan dashboard
        $jumlahMahasiswa = Mahasiswa::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', Carbon::today())->count();

        // Ambil semua mahasiswa untuk form tambah absensi
        $mahasiswas = Mahasiswa::all();

        // Cek apakah mahasiswa yang login sudah absen hari ini
        $user = Auth::user();
        $absenHariIni = false;

        if ($user) {
            $absenHariIni = Absensi::whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('nim', $user->nim);
            })->whereDate('tanggal', now()->toDateString())->exists();
        }

        return view('absensi.index', compact(
            'absensis',
            'mahasiswas',
            'absenHariIni',
            'jumlahMahasiswa',
            'jumlahKegiatan',
            'jumlahAbsensiHariIni'
        ));
    }

    /**
     * Simpan data absensi baru.
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

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit absensi.
     */
    public function edit($id)
    {
        $absen = Absensi::findOrFail($id);
        $mahasiswas = Mahasiswa::all();

        return view('absensi.edit', compact('absen', 'mahasiswas'));
    }

    /**
     * Update data absensi.
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

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Hapus data absensi.
     */
    public function destroy($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
