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
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diunggah!');
    }
    // Pembimbing menyetujui laporan
    public function setujui($id)
    {
        $laporan = Laporan::findOrFail($id);

        // hanya pembimbing pemilik yg boleh setujui
        if (Auth::user()->id !== $laporan->user->pembimbing_id) {
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

        if (Auth::user()->id !== $laporan->user->pembimbing_id) {
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
            abort(404, 'File sertifikat tidak ditemukan.');
        }

        return response()->download($filePath);
    }
    
    public function downloadLaporan($id)
    {
        $laporan = \App\Models\Laporan::findOrFail($id);

        return response()->download(storage_path('app/' . $laporan->file));
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
