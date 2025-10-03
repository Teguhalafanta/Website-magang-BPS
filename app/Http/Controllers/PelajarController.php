<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelajar;
use App\Notifications\NotifikasiBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'email'           => Auth::user()->email,
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

        // === Kirim notifikasi ke admin ===
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifikasiBaru(
                'Ada pengajuan magang baru dari ' . $request->nama,
                route('admin.pengajuan.index') // sesuaikan dengan route daftar pengajuan admin kamu
            ));
        }

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

    // pelajar edit data pengajuan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'nim_nisn' => 'nullable|string|max:50',
            'asal_institusi' => 'nullable|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'rencana_mulai' => 'nullable|date',
            'rencana_selesai' => 'nullable|date',
            'proposal' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'surat_pengajuan' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        $pengajuan = \App\Models\Pelajar::findOrFail($id);

        $pengajuan->fill($request->except(['proposal', 'surat_pengajuan']));

        if ($request->hasFile('proposal')) {
            $pengajuan->proposal = $request->file('proposal')->store('proposal', 'public');
        }

        if ($request->hasFile('surat_pengajuan')) {
            $pengajuan->surat_pengajuan = $request->file('surat_pengajuan')->store('surat_pengajuan', 'public');
        }

        $pengajuan->save();

        return redirect()->route('pelajar.pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    // pelajar hapus data pengajuan
    public function destroy($id)
    {
        $pengajuan = Pelajar::findOrFail($id);

        // hapus file (jika ada)
        if ($pengajuan->proposal) {
            Storage::delete($pengajuan->proposal);
        }
        if ($pengajuan->surat_pengajuan) {
            Storage::delete($pengajuan->surat_pengajuan);
        }

        $pengajuan->delete();

        return redirect()->route('pelajar.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
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
