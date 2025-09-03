<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Absensi;

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar kegiatan user login.
     */
    public function index()
    {
        $pelajar = Auth::user()->pelajar;

        if (!$pelajar) {
            return redirect('/')->with('error', 'Akun tidak memiliki data pelajar.');
        }

        $kegiatans = Kegiatan::where('pelajar_id', $pelajar->id_pelajar)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $absenHariIni = Absensi::where('id_pelajar', $pelajar->id_pelajar)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        return view('kegiatan.index', compact('kegiatans', 'pelajar', 'absenHariIni'));
    }

    /**
     * Kegiatan Harian.
     */
    public function harian()
    {
        $pelajar = Auth::user()->pelajar;
        $kegiatans = Kegiatan::where('pelajar_id', $pelajar->id_pelajar)
            ->whereDate('tanggal', now()->toDateString())
            ->get();

        return view('kegiatan.harian', compact('kegiatans'));
    }

    /**
     * Kegiatan Bulanan.
     */
    public function bulanan()
    {
        $pelajar = Auth::user()->pelajar;
        $kegiatans = Kegiatan::where('pelajar_id', $pelajar->id_pelajar)
            ->whereMonth('tanggal', now()->month)
            ->get();

        return view('kegiatan.bulanan', compact('kegiatans'));
    }

    /**
     * Simpan kegiatan baru (AJAX).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        $pelajar = Auth::user()->pelajar;

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'pelajar_id' => $pelajar->id_pelajar,
        ]);

        return response()->json(['success' => true, 'kegiatan' => $kegiatan]);
    }

    /**
     * Tampilkan form edit (untuk AJAX, optional).
     */
    public function edit(Kegiatan $kegiatan)
    {
        $pelajar = Auth::user()->pelajar;
        if ($kegiatan->pelajar_id !== $pelajar->id_pelajar) {
            return response()->json(['error' => 'Tidak bisa mengedit kegiatan ini.'], 403);
        }
        return response()->json($kegiatan);
    }

    /**
     * Update kegiatan (AJAX).
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $pelajar = Auth::user()->pelajar;
        if ($kegiatan->pelajar_id !== $pelajar->id_pelajar) {
            return response()->json(['error' => 'Tidak bisa update kegiatan ini.'], 403);
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        $kegiatan->update($request->only('nama_kegiatan', 'tanggal', 'deskripsi'));

        return response()->json(['success' => true, 'kegiatan' => $kegiatan]);
    }

    /**
     * Hapus kegiatan (AJAX).
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $pelajar = Auth::user()->pelajar;
        if ($kegiatan->pelajar_id !== $pelajar->id_pelajar) {
            return response()->json(['error' => 'Tidak bisa hapus kegiatan ini.'], 403);
        }

        $kegiatan->delete();
        return response()->json(['success' => true, 'id' => $kegiatan->id]);
    }
}
