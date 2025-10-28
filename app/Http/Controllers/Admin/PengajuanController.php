<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index()
    {
        // Admin bisa lihat semua pengajuan
        $pengajuans = Pelajar::with('user')->latest()->get();

        return view('admin.pengajuan.daftar', compact('pengajuans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan' => 'nullable|string',
            'surat_penerimaan' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $pengajuan = Pelajar::findOrFail($id);

        $pengajuan->status = $request->status;
        $pengajuan->alasan = $request->alasan;

        // Simpan file surat penerimaan jika status disetujui dan file dikirim
        if ($request->status === 'disetujui' && $request->hasFile('surat_penerimaan')) {
            // Hapus file lama bila ada
            if ($pengajuan->surat_penerimaan) {
                Storage::delete('public/' . $pengajuan->surat_penerimaan);
            }

            $filePath = $request->file('surat_penerimaan')->store('surat_penerimaan', 'public');
            $pengajuan->surat_penerimaan = $filePath;
        }

        $pengajuan->save();

        return redirect()->route('admin.pengajuan.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pelajar::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'required|email',
            'nim_nisn' => 'nullable|string|max:50',
            'asal_institusi' => 'required|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'rencana_mulai' => 'nullable|date',
            'rencana_selesai' => 'nullable|date',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx',
            'surat_pengajuan' => 'nullable|file|mimes:pdf,doc,docx',
        ]);

        $pengajuan->nama = $request->nama;
        $pengajuan->jenis_kelamin = $request->jenis_kelamin;
        $pengajuan->tempat_lahir = $request->tempat_lahir;
        $pengajuan->tanggal_lahir = $request->tanggal_lahir;
        $pengajuan->alamat = $request->alamat;
        $pengajuan->telepon = $request->telepon;
        $pengajuan->email = $request->email;
        $pengajuan->nim_nisn = $request->nim_nisn;
        $pengajuan->asal_institusi = $request->asal_institusi;
        $pengajuan->fakultas = $request->fakultas;
        $pengajuan->jurusan = $request->jurusan;
        $pengajuan->rencana_mulai = $request->rencana_mulai;
        $pengajuan->rencana_selesai = $request->rencana_selesai;

        // Handle file upload
        if ($request->hasFile('proposal')) {
            if ($pengajuan->proposal) {
                Storage::delete('public/' . $pengajuan->proposal);
            }
            $pengajuan->proposal = $request->file('proposal')->store('pengajuan', 'public');
        }

        if ($request->hasFile('surat_pengajuan')) {
            if ($pengajuan->surat_pengajuan) {
                Storage::delete('public/' . $pengajuan->surat_pengajuan);
            }
            $pengajuan->surat_pengajuan = $request->file('surat_pengajuan')->store('pengajuan', 'public');
        }

        $pengajuan->save();

        return redirect()->route('admin.pengajuan.index')->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengajuan = Pelajar::findOrFail($id);

        // hapus file jika ada
        if ($pengajuan->proposal) {
            Storage::delete('public/' . $pengajuan->proposal);
        }
        if ($pengajuan->surat_pengajuan) {
            Storage::delete('public/' . $pengajuan->surat_pengajuan);
        }

        $pengajuan->delete();

        return redirect()->route('admin.pengajuan.index')->with('success', 'Data pengajuan berhasil dihapus.');
    }
}
