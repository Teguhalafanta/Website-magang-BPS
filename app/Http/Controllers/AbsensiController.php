<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pelajar;

class AbsensiController extends Controller
{
    /**
     * Tampilkan daftar absensi, khusus untuk pelajar yang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Ambil pelajar yang terkait user login
        $pelajar = Pelajar::where('user_id', $user->id)->first();

        if (!$pelajar) {
            // Jika tidak ada pelajar terkait, kosongkan hasilnya
            $absensis = collect();
            $pelajar_id = null;
        } else {
            $pelajar_id = $pelajar->id_pelajar;

            if ($request->has('today')) {
                $absensis = Absensi::with('pelajar')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->where('pelajar_id', $pelajar_id)
                    ->get();
            } else {
                $absensis = Absensi::with('pelajar')
                    ->where('pelajar_id', $pelajar_id)
                    ->paginate(10);
            }
        }

        // Data tambahan untuk dashboard
        $jumlahPelajar = Pelajar::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('tanggal', date('Y-m-d'))->count();

        // Semua pelajar (jika dibutuhkan untuk form tambah absensi)
        $pelajars = Pelajar::all();

        // Cek apakah pelajar sudah absen hari ini
        $absenHariIni = $pelajar_id 
            ? Absensi::where('pelajar_id', $pelajar_id)
                ->whereDate('tanggal', date('Y-m-d'))
                ->exists()
            : false;

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
            'pelajar_id' => 'required|exists:pelajars,id_pelajar',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
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
            'pelajar_id' => 'required|exists:pelajars,id_pelajar',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
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
