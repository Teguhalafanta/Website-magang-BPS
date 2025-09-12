<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Auth;
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

    // Tampilkan kegiatan bulanan user (bulan dan tahun sekarang)
    public function kegiatanBulanan(Request $request)
    {
        $user = Auth::user();
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        [$tahun, $bulanNum] = explode('-', $bulan);

        $kegiatans = Kegiatan::where('user_id', $user->id)
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
            'status' => 'nullable|string|in:Belum,Proses,Selesai',
            'bukti_dukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $data = $validated;
        $data['user_id'] = Auth::id();

        if ($request->hasFile('bukti_dukung')) {
            $filePath = $request->file('bukti_dukung')->store('bukti', 'public');
            $data['bukti_dukung'] = $filePath;
        }

        // Mapping status → ke kolom status_penyelesaian
        $data['status_penyelesaian'] = $data['status'] ?? 'Belum';
        unset($data['status']);

        Kegiatan::create($data);

        return redirect()->route('pelajar.kegiatan.harian')->with('success', 'Kegiatan berhasil ditambahkan');
    }

    // Tampilkan detail kegiatan
    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kegiatan.show', compact('kegiatan'));
    }

    // Tampilkan form edit kegiatan
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        return view('kegiatan.edit', compact('kegiatan'));
    }

    // Update data kegiatan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable',
            'volume' => 'nullable|numeric',
            'satuan' => 'nullable|string',
            'durasi' => 'required|numeric',
            'pemberi_tugas' => 'nullable|string',
            'tim_kerja' => 'nullable|string',
            'status' => 'required|string|in:Belum,Proses,Selesai',
            'bukti_dukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        $data = $validated;

        if ($request->hasFile('bukti_dukung')) {
            $filePath = $request->file('bukti_dukung')->store('bukti', 'public');
            $data['bukti_dukung'] = $filePath;
        }

        // Mapping status → ke kolom status_penyelesaian
        $data['status_penyelesaian'] = $data['status'];
        unset($data['status']);

        $kegiatan->update($data);

        return redirect()->route('pelajar.kegiatan.harian')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    // Hapus data kegiatan
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
