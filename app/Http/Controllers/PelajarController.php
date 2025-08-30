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

    // Simpan data pengajuan ke tabel pelajars
    public function store(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:255',
            'jenis_kelamin'         => 'required|in:Laki-laki,Perempuan',
            'tempat_tanggal_lahir'  => 'required|string|max:255',
            'alamat'                => 'required|string',
            'telepon'               => 'nullable|string|max:20',
            'email'                 => 'required|email|unique:pelajars,email',
            'nim_nisn'              => 'required|string|unique:pelajars,nim_nisn',
            'asal_institusi'        => 'required|string|max:255',
            'fakultas'              => 'nullable|string|max:255',
            'jurusan'               => 'required|string|max:255',
            'rencana_mulai'         => 'required|date',
            'rencana_selesai'       => 'required|date|after_or_equal:rencana_mulai',
        ]);

        Pelajar::create([
            'id_user'               => Auth::user()->id_user, // foreign key dari users
            'nama'                  => $request->nama,
            'jenis_kelamin'         => $request->jenis_kelamin,
            'tempat_tanggal_lahir'  => $request->tempat_tanggal_lahir,
            'alamat'                => $request->alamat,
            'telepon'               => $request->telepon,
            'email'                 => $request->email,
            'nim_nisn'              => $request->nim_nisn,
            'asal_institusi'        => $request->asal_institusi,
            'fakultas'              => $request->fakultas,
            'jurusan'               => $request->jurusan,
            'rencana_mulai'         => $request->rencana_mulai,
            'rencana_selesai'       => $request->rencana_selesai,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Pengajuan magang berhasil dikirim.');
    }

    // untuk menampilkan pelajar yang telah mengajukan magang
    public function index()
    {
        $pengajuans = Pelajar::with('user')->latest()->get();

        return view('pelajar.daftar_pengajuan', compact('pengajuans'));
    }
}
