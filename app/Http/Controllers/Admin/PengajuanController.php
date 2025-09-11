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
        $pengajuan = Pelajar::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return redirect()->route('admin.pengajuan.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function edit($id)
    {
        $pengajuan = Pelajar::findOrFail($id);
        return view('admin.pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pelajar::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'asal_institusi' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'email' => 'required|email',
            'telepon' => 'nullable|string|max:20',
        ]);

        $pengajuan->update($request->all());

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
