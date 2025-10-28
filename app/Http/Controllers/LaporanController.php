<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // Halaman utama laporan
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelajar') {
            // Pelajar hanya melihat laporannya sendiri
            $laporan = Laporan::where('user_id', $user->id)->first();
            return view('pelajar.laporan.index', compact('laporan'));
        }

        if ($user->role === 'pembimbing') {
            // Pembimbing hanya melihat laporan peserta bimbingannya
            $laporans = Laporan::whereHas('user', function ($query) use ($user) {
                $query->where('pembimbing_id', $user->id);
            })->with('user')->get();

            return view('pembimbing.laporan.index', compact('laporans'));
        }

        if ($user->role === 'admin') {
            // Admin melihat semua laporan
            $laporans = Laporan::with('user')->get();
            return view('admin.laporan.index', compact('laporans'));
        }

        abort(403, 'Akses tidak diizinkan.');
    }

    // Upload laporan (hanya pelajar)
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = Auth::user();

        if ($user->role !== 'pelajar') {
            abort(403, 'Hanya pelajar yang dapat mengunggah laporan.');
        }

        // Hapus laporan lama jika ada
        $old = Laporan::where('user_id', $user->id)->first();
        if ($old) {
            Storage::disk('public')->delete($old->file);
            $old->delete();
        }

        // Simpan laporan baru
        $path = $request->file('file')->store('laporan', 'public');

        Laporan::create([
            'user_id' => $user->id,
            'file' => $path,
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diunggah!');
    }

    // Download laporan (admin & pembimbing bisa semua, pelajar hanya miliknya)
    public function download($id)
    {
        $laporan = Laporan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'pelajar' && $laporan->user_id !== $user->id) {
            abort(403, 'Kamu tidak boleh mengunduh laporan orang lain.');
        }

        if ($user->role === 'pembimbing') {
            $isBimbingan = $laporan->user && $laporan->user->pembimbing_id == $user->id;
            if (!$isBimbingan) {
                abort(403, 'Laporan ini bukan peserta bimbingan kamu.');
            }
        }

        // Admin tidak dibatasi

        $filePath = storage_path('app/public/' . $laporan->file);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath);
    }
}
