<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pelajar;
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
            $absensis = Absensi::with('pelajar')
                ->whereDate('tanggal', now()->toDateString())
                ->get();
        } else {
            $absensis = Absensi::with('pelajar')->paginate(10);
        }

        // Data tambahan untuk tampilan dashboard
        $jumlahPelajar = Pelajar::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', Carbon::today())->count();

        // Ambil semua pelajar untuk form tambah absensi
        $pelajars = Pelajar::all();

        // Cek apakah pelajar yang login sudah absen hari ini
        $user = Auth::user();
        $absenHariIni = false;

        if ($user) {
            $absenHariIni = Absensi::whereHas('pelajar', function ($query) use ($user) {
                $query->where('id_pelajar', $user->id_user);
            })->whereDate('tanggal', now()->toDateString())->exists();
        }

        return view('absensi.index', compact(
            'absensis',
            'pelajars',
            'absenHariIni',
            'jumlahPelajar',
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
            'pelajar_id' => 'required|exists:pelajars,id',
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
        $pelajars = Pelajar::all();

        return view('absensi.edit', compact('absen', 'pelajars'));
    }

    /**
     * Update data absensi.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pelajar_id' => 'required|exists:pelajars,id',
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
