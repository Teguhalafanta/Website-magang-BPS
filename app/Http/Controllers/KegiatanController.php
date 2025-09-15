<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::paginate(10);
        return view('kegiatan.index', compact('kegiatans'));
    }

    // Tampilkan kegiatan harian user (tanggal hari ini)
    public function harian()
    {
        $today = now()->format('Y-m-d');

        $kegiatans = Kegiatan::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->get();

        return view('kegiatan.harian', compact('kegiatans'));
    }

    // Tampilkan kegiatan bulanan user (bulan & tahun sekarang)
    public function kegiatanBulanan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        [$tahun, $bulanNum] = explode('-', $bulan);

        $kegiatans = Kegiatan::where('user_id', Auth::id())
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->get();

        return view('kegiatan.bulanan', compact('kegiatans', 'bulan'));
    }

    // Simpan data kegiatan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'volume' => 'nullable|integer|min:0',
            'satuan' => 'nullable|string|max:100',
            'durasi' => 'nullable|integer|min:0',
            'pemberi_tugas' => 'nullable|string|max:255',
            'tim_kerja' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:Belum Dimulai,Dalam Proses,Selesai',
            'bukti_dukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $data = $validated;
        $data['user_id'] = Auth::id();
        $data['status_penyelesaian'] = $validated['status'] ?? 'Belum Dimulai';

        if ($request->hasFile('bukti_dukung')) {
            $data['bukti_dukung'] = $request->file('bukti_dukung')->store('bukti', 'public');
        }

        Kegiatan::create($data);

        return redirect()->route('pelajar.kegiatan.harian')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    // Detail kegiatan
    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kegiatan.show', compact('kegiatan'));
    }

    // Form edit
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'volume' => 'nullable|integer|min:0',
            'satuan' => 'nullable|string|max:100',
            'durasi' => 'required|integer|min:0',
            'pemberi_tugas' => 'nullable|string|max:255',
            'tim_kerja' => 'nullable|string|max:255',
            'status' => 'required|string|in:Belum,Proses,Selesai',
            'bukti_dukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        // Map input status → status_penyelesaian
        $validated['status_penyelesaian'] = $validated['status'];
        unset($validated['status']);

        if ($request->hasFile('bukti_dukung')) {
            if ($kegiatan->bukti_dukung && Storage::disk('public')->exists($kegiatan->bukti_dukung)) {
                Storage::disk('public')->delete($kegiatan->bukti_dukung);
            }
            $validated['bukti_dukung'] = $request->file('bukti_dukung')->store('bukti', 'public');
        }

        $kegiatan->update($validated);

        return redirect()->route('pelajar.kegiatan.harian')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    // Hapus kegiatan
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->bukti_dukung && Storage::disk('public')->exists($kegiatan->bukti_dukung)) {
            Storage::disk('public')->delete($kegiatan->bukti_dukung);
        }

        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
