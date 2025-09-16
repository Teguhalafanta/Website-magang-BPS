<?php

namespace App\Http\Controllers;

use App\Models\Pelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelajarController extends Controller
{
    // Form pengajuan magang
    public function create()
    {
        return view('pelajar.pengajuan_pelajar');
    }

    // Simpan pengajuan baru
    public function store(Request $request)
    {
        // Cek apakah user sudah pernah mengajukan
        $sudahAda = Pelajar::where('user_id', Auth::id())->exists();
        if ($sudahAda) {
            return redirect()->route('pelajar.pengajuan.index')
                ->with('error', 'Anda sudah pernah mengajukan magang, tidak bisa mengajukan lagi.');
        }

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
            'proposal'        => 'required|mimes:pdf,doc,docx|max:2048',
            'surat_pengajuan' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        // Simpan file
        $proposalPath = $request->file('proposal')->store('proposals', 'public');
        $suratPath    = $request->file('surat_pengajuan')->store('surat_pengajuan', 'public');

        // Simpan data pelajar
        Pelajar::create([
            'user_id'         => Auth::id(),
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
            'proposal'        => $proposalPath,
            'surat_pengajuan' => $suratPath,
            'status'          => 'diajukan',
        ]);

        return redirect()->route('pelajar.pengajuan.index')
            ->with('success', 'Pengajuan magang berhasil dikirim.');
    }

    // Pelajar lihat daftar pengajuan miliknya
    public function index()
    {
        $pengajuans = Pelajar::where('user_id', Auth::id())
            ->with('user')
            ->latest()
            ->get();

        return view('pelajar.daftar_pengajuan', compact('pengajuans'));
    }

    // Admin update status & alasan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan' => 'nullable|string',
        ]);

        $pengajuan = Pelajar::findOrFail($id);
        $pengajuan->update([
            'status' => $request->status,
            'alasan' => $request->alasan,
        ]);

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
