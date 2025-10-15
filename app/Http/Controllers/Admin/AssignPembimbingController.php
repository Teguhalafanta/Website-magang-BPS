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

        // Ambil pelajar dan pembimbing
        $pelajar = Pelajar::findOrFail($id);
        $pembimbing = Pembimbing::with('user')->findOrFail($request->pembimbing_id);

        // Simpan relasi pembimbing ke pelajar
        $pelajar->update([
            'pembimbing_id' => $pembimbing->id,
        ]);

        // === Ambil user pelajar dan pembimbing ===
        $userPelajar = $pelajar->user;
        $userPembimbing = $pembimbing->user;

        // === Kirim notifikasi ke pelajar ===
        $userPelajar->notify(new NotifikasiBaru(
            'Kamu telah mendapatkan pembimbing: ' . $pembimbing->nama,
            route('profile.show')
        ));

        // === Kirim notifikasi ke pembimbing ===
        $userPembimbing->notify(new NotifikasiBaru(
            'Kamu telah ditugaskan membimbing pelajar: ' . $pelajar->nama,
            route('pembimbing.bimbingan')
        ));

        return back()->with('success', 'Pembimbing berhasil ditetapkan!');
    }
}
