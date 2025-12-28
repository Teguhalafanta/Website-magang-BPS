<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use App\Models\Pembimbing;
use App\Notifications\NotifikasiBaru;
use Illuminate\Http\Request;

class AssignPembimbingController extends Controller
{
    public function index()
    {
        $pelajars = Pelajar::where('status', 'disetujui')->with('pembimbing')->get();
        $pembimbings = Pembimbing::with('user')->get();
        return view('admin.pelajar.assignpembimbing', compact('pelajars', 'pembimbings'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'pembimbing_id' => 'required|exists:pembimbings,id',
        ]);

        // Ambil peserta & pembimbing
        $pelajar = Pelajar::findOrFail($id);
        $pembimbing = Pembimbing::with('user')->findOrFail($request->pembimbing_id);

        // Simpan relasi pembimbing ke peserta (tabel pelajars)
        $pelajar->update([
            'pembimbing_id' => $pembimbing->id,
        ]);

        // === Simpan pembimbing_id ke table users (ini yang penting!) ===
        $userPelajar = $pelajar->user;
        $userPelajar->update([
            'pembimbing_id' => $pembimbing->user->id, // Sesuaikan jika kolomnya berbeda
        ]);

        // === Kirim notifikasi ke peserta ===
        $userPelajar->notify(new NotifikasiBaru(
            'Kamu telah mendapatkan pembimbing: ' . $pembimbing->nama,
            route('profile.show')
        ));

        // === Kirim notifikasi ke pembimbing ===
        $userPembimbing = $pembimbing->user;
        $userPembimbing->notify(new NotifikasiBaru(
            'Kamu telah ditugaskan membimbing peserta: ' . $pelajar->nama,
            route('pembimbing.bimbingan')
        ));

        return back()->with('success', 'Pembimbing berhasil ditetapkan!');
    }
}
