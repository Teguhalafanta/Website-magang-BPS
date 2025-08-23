<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Tampilkan halaman absensi dengan daftar absensi
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // pastikan user sudah login

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $today = now()->toDateString();

        // Cek apakah user belum absen hari ini
        $belumAbsen = !Absensi::where('mahasiswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        // Ambil semua data absensi dan mahasiswa
        $absensis = Absensi::with('mahasiswa')->orderByDesc('created_at')->get();
        $mahasiswas = Mahasiswa::all();

        return view('absensi.index', compact('absensis', 'mahasiswas', 'belumAbsen'));
    }

    /**
     * Simpan data absensi
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data mahasiswa dari user
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'Data mahasiswa tidak ditemukan.']);
        }

        // Simpan data absensi
        Absensi::create([
            'mahasiswa_id'   => $mahasiswa->id,
            'nama_mahasiswa' => $mahasiswa->nama,
            'nim'            => $mahasiswa->nim,
            'status'         => $request->status,
            'tanggal'        => now()->toDateString(),
        ]);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan.');
    }
}
