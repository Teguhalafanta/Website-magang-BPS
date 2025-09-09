<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    // Tampilkan kegiatan harian user (tanggal hari ini)
    public function harian()
    {
        $today = now()->toDateString();

        $kegiatans = Kegiatan::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->get();

        return view('kegiatan.harian', compact('kegiatans', 'today'));
    }

    // Tampilkan kegiatan bulanan user (bulan dan tahun sekarang)
    public function kegiatanBulanan(Request $request)
    {
        $user = Auth::user();

        // Ambil bulan yang dipilih dari request (default: bulan ini)
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));

        // Ambil tahun & bulan dari string (format: YYYY-MM)
        [$tahun, $bulanNum] = explode('-', $bulan);

        // Ambil kegiatan user sesuai bulan
        $kegiatans = Kegiatan::where('user_id', $user->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->get();

        return view('kegiatan.bulanan', compact('kegiatans', 'bulan'));
    }

    // Simpan data kegiatan baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'volume' => 'nullable|integer|min:0',
            'satuan' => 'nullable|string|max:100',
            'durasi' => 'nullable|integer|min:0',
            'pemberi_tugas' => 'nullable|string|max:255',
            'tim_kerja' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Belum,Proses,Selesai',
        ]);

        Kegiatan::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'volume' => $request->volume,
            'satuan' => $request->satuan,
            'durasi' => $request->durasi,
            'pemberi_tugas' => $request->pemberi_tugas,
            'tim_kerja' => $request->tim_kerja,
            'status' => $request->status ?? 'Belum',
        ]);

        return redirect()->route('pelajar.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan');
    }

    // Tampilkan form edit kegiatan (misal modal ajax)
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $html = view('kegiatan._form_edit', compact('kegiatan'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    // Update data kegiatan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable',
            'volume' => 'nullable|numeric',
            'satuan' => 'nullable|string',
            'durasi' => 'required|numeric',
            'pemberi_tugas' => 'nullable|string',
            'tim_kerja' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->all());

        return redirect()->back()->with('success', 'Kegiatan berhasil diperbarui.');
    }


    // Hapus data kegiatan
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
