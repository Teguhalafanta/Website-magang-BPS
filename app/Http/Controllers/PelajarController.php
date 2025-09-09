<?php

namespace App\Http\Controllers;

use App\Models\Pelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelajarController extends Controller
{
    public function create()
    {
        return view('pelajar.pengajuan_pelajar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'    => 'required|string|max:255',
            'tanggal_lahir'   => 'required|date',
            'alamat'          => 'required|string',
            'telepon'         => 'nullable|string|max:20',
            'email'           => 'required|email|unique:pelajars,email',
            'nim_nisn'        => 'required|string|unique:pelajars,nim_nisn',
            'asal_institusi'  => 'required|string|max:255',
            'fakultas'        => 'nullable|string|max:255',
            'jurusan'         => 'required|string|max:255',
            'rencana_mulai'   => 'required|date',
            'rencana_selesai' => 'required|date|after_or_equal:rencana_mulai',
        ]);

        Pelajar::create([
            'id_user'         => Auth::id(),
            'nama'            => $request->nama,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'tempat_lahir'    => $request->tempat_lahir,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'alamat'          => $request->alamat,
            'telepon'         => $request->telepon,
            'email'           => $request->email,
            'nim_nisn'        => $request->nim_nisn,
            'asal_institusi'  => $request->asal_institusi,
            'fakultas'        => $request->fakultas,
            'jurusan'         => $request->jurusan,
            'rencana_mulai'   => $request->rencana_mulai,
            'rencana_selesai' => $request->rencana_selesai,
        ]);

        return redirect()->route('pelajar.pengajuan.index')
            ->with('success', 'Pengajuan magang berhasil dikirim.');
    }

    public function index()
    {
        // Pelajar hanya bisa lihat pengajuan miliknya sendiri
        $pengajuans = Pelajar::where('id_user', Auth::id())
            ->with('user')
            ->latest()
            ->get();

        return view('pelajar.daftar_pengajuan', compact('pengajuans'));
    }
}
