<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use App\Models\Pembimbing;
use App\Models\User; 
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

        $pelajar = Pelajar::findOrFail($id);
        $pelajar->pembimbing_id = $request->pembimbing_id;
        $pelajar->save();

        // === Ambil user pelajar dan pembimbing ===
        $userPelajar = $pelajar->user; // relasi pelajar → user
        $pembimbing = Pembimbing::with('user')->findOrFail($request->pembimbing_id);
        $userPembimbing = $pembimbing->user;

        // === Kirim notifikasi ke pelajar ===
        $userPelajar->notify(new NotifikasiBaru(
            'Kamu telah mendapatkan pembimbing: ' . $userPembimbing->name,
            route('pelajar.pengajuan.index') // ubah sesuai route pelajar kamu
        ));

        // === Kirim notifikasi ke pembimbing ===
        $userPembimbing->notify(new NotifikasiBaru(
            'Kamu telah ditugaskan membimbing pelajar: ' . $userPelajar->name,
            route('pembimbing.dashboard') // ubah sesuai route dashboard pembimbing kamu
        ));

        return back()->with('success', 'Pembimbing berhasil ditetapkan!');
    }
}
