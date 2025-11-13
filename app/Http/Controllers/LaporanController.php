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
            $pembimbing = $user->pembimbing;
            $laporans = Laporan::whereHas('user.pelajar', function ($query) use ($pembimbing) {
                $query->where('pembimbing_id', $pembimbing->id ?? 0);
            })->with('user.pelajar')->get();

            return view('pembimbing.laporan.index', compact('laporans'));
        }

        if ($user->role === 'admin') {
            // Admin melihat semua laporan
            $laporans = Laporan::with('user')->get();
            return view('admin.laporan.index', compact('laporans'));
        }

        abort(403, 'Akses tidak diizinkan.');
    }

    public function create()
    {
        return view('pelajar.laporan.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file_laporan' => 'required|mimes:pdf|max:2048'
        ]);

        $path = $request->file('file_laporan')->store('laporan', 'public');

        Laporan::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'file' => $path,
                'status' => 'menunggu'
            ]
        );

        // === Kirim notifikasi ke pembimbing ===
        $pelajar = \App\Models\Pelajar::where('user_id', Auth::id())->first();

        if ($pelajar && $pelajar->pembimbing && $pelajar->pembimbing->user) {
            $pembimbingUser = $pelajar->pembimbing->user;

            $pembimbingUser->notify(new \App\Notifications\NotifikasiBaru(
                $pelajar->nama . ' mengunggah laporan akhir untuk diverifikasi.',
                route('pembimbing.laporan')
            ));
        }

        return back()->with('success', 'Laporan berhasil diupload!');
    }

    // Pembimbing menyetujui laporan
    public function setujui($id)
    {
        $laporan = Laporan::findOrFail($id);
        // hanya pembimbing pemilik yg boleh setujui
        $pembimbing = Auth::user()->pembimbing;
        if (!isset($laporan->user) || !isset($laporan->user->pelajar) || !$pembimbing || $laporan->user->pelajar->pembimbing_id != $pembimbing->id) {
            abort(403, 'Tidak berwenang menyetujui laporan ini.');
        }

        $laporan->update([
            'status' => 'disetujui'
        ]);

        return back()->with('success', 'Laporan telah disetujui dan dikirim ke admin.');
    }

    // Pembimbing menolak laporan
    public function tolak($id)
    {
        $laporan = Laporan::findOrFail($id);
        $pembimbing = Auth::user()->pembimbing;
        if (!isset($laporan->user) || !isset($laporan->user->pelajar) || !$pembimbing || $laporan->user->pelajar->pembimbing_id != $pembimbing->id) {
            abort(403, 'Tidak berwenang menolak laporan ini.');
        }

        $laporan->update([
            'status' => 'ditolak'
        ]);

        return back()->with('error', 'Laporan ditolak. Pelajar harus upload ulang.');
    }

    public function halamanPembimbing()
    {
        $pembimbing = Auth::user()->pembimbing;

        $laporans = Laporan::whereIn('user_id', function ($q) use ($pembimbing) {
            $q->select('user_id')->from('pelajars')->where('pembimbing_id', $pembimbing->id);
        })->get();

        return view('pembimbing.laporan.index', compact('laporans'));
    }


    // Halaman Admin Kelola Sertifikat
    public function adminSertifikat()
    {
        $laporans = Laporan::where('status', 'disetujui')->with('user')->get();

        return view('admin.sertifikat.index', compact('laporans'));
    }

    // Admin upload sertifikat
    public function uploadSertifikat(Request $request, $id)
    {
        // pastikan hanya admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengunggah sertifikat.');
        }

        $request->validate([
            'file_sertifikat' => 'required|mimes:pdf|max:2048'
        ], [
            'file_sertifikat.required' => 'File sertifikat wajib diunggah.',
            'file_sertifikat.mimes' => 'Sertifikat harus berupa file PDF.',
            'file_sertifikat.max' => 'Ukuran sertifikat maksimal 2MB.'
        ]);

        $laporan = Laporan::findOrFail($id);

        // Hapus sertifikat lama jika ada
        if ($laporan->file_sertifikat) {
            Storage::disk('public')->delete($laporan->file_sertifikat);
        }

        // Simpan sertifikat baru
        $path = $request->file('file_sertifikat')->store('sertifikat', 'public');

        $laporan->update([
            'file_sertifikat' => $path,
            'status' => 'selesai'
        ]);

        return redirect()->back()->with('success', 'Sertifikat berhasil diupload dan dikirim ke pelajar.');
    }

    // Pelajar download sertifikat
    public function downloadSertifikat($id)
    {
        $laporan = Laporan::findOrFail($id);
        $user = Auth::user();

        // Pastikan hanya pemilik sertifikat yang bisa download
        if ($user->role !== 'pelajar' || $laporan->user_id !== $user->id) {
            abort(403, 'Kamu tidak diperbolehkan mengunduh sertifikat ini.');
        }

        // Sertifikat belum tersedia
        if (!$laporan->file_sertifikat) {
            return back()->with('error', 'Sertifikat belum tersedia.');
        }

        $filePath = storage_path('app/public/' . $laporan->file_sertifikat);

        if (!file_exists($filePath)) {
            // Instead of aborting (which renders errors.404), return user back with a clear message
            return back()->with('error', 'File sertifikat tidak ditemukan di server.');
        }

        return response()->download($filePath);
    }

    public function downloadLaporan($id)
    {
        $laporan = \App\Models\Laporan::findOrFail($id);

        // Use the 'file' column (stored via storage disk 'public')
        $filePath = storage_path('app/public/' . $laporan->file);
        if (!file_exists($filePath)) {
            abort(404, 'File laporan tidak ditemukan.');
        }

        return response()->download($filePath);
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
            $pembimbing = $user->pembimbing;
            $isBimbingan = $laporan->user && isset($laporan->user->pelajar) && ($laporan->user->pelajar->pembimbing_id == ($pembimbing->id ?? null));
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
