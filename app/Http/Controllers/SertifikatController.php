<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    public function index()
    {
        $laporans = Laporan::whereIn('status', ['disetujui', 'selesai'])
            ->with('user.pelajar')
            ->get();

        return view('admin.sertifikat.index', compact('laporans'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'file_sertifikat' => 'required|mimes:pdf|max:2048',
        ]);

        $laporan = Laporan::findOrFail($id);

        $path = $request->file('file_sertifikat')->store('sertifikat', 'public');

        $laporan->update([
            'file_sertifikat' => $path,
            'status' => 'selesai' // ← supaya sertifikat muncul di siswa
        ]);

        // === KIRIM NOTIFIKASI KE PESERTA ===
        $pelajarUser = \App\Models\User::find($laporan->user_id);

        if ($pelajarUser) {
            $pesan = 'Sertifikat magang kamu telah tersedia dan bisa diunduh.';
            $url = route('pelajar.laporan.index');

            $pelajarUser->notify(new \App\Notifications\NotifikasiBaru($pesan, $url));
        }

        return back()->with('success', 'Sertifikat berhasil dikirim.');
    }
}
