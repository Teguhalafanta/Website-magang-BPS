<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pelajar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role == 'pelajar') {
            $kegiatans = Kegiatan::where('user_id', $user->id)
                ->latest()
                ->paginate(10);

            return view('kegiatan.index', compact('kegiatans'));
        }

        if ($user->role == 'pembimbing') {
            // Ambil semua pelajar yang dibimbing
            $pelajarIds = Pelajar::where('pembimbing_id', $user->id)->pluck('user_id');

            // Filter kegiatan berdasarkan pelajar bimbingan
            $kegiatans = Kegiatan::whereIn('user_id', $pelajarIds)
                ->when($request->search, function ($query) use ($request) {
                    $query->where('nama_kegiatan', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        });
                })
                ->latest()
                ->paginate(10);

            return view('pembimbing.kegiatan', compact('kegiatans'));
        }

        // Jika bukan pelajar/pembimbing
        abort(403, 'Akses tidak diizinkan');
    }

    public function harian()
    {
        $today = now()->format('Y-m-d');

        $kegiatans = Kegiatan::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->get();

        return view('kegiatan.harian', compact('kegiatans'));
    }

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

    public function show($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if (Auth::user()->role == 'pembimbing') {
            $pelajarIds = Pelajar::where('pembimbing_id', Auth::id())->pluck('user_id');
            if (!$pelajarIds->contains($kegiatan->user_id)) {
                abort(403, 'Anda tidak memiliki akses ke kegiatan ini');
            }
        }

        return view('kegiatan.show', compact('kegiatan'));
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if (Auth::id() != $kegiatan->user_id) {
            abort(403, 'Tidak diizinkan mengedit kegiatan ini');
        }

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
            'durasi' => 'nullable|integer|min:0',
            'pemberi_tugas' => 'nullable|string|max:255',
            'tim_kerja' => 'nullable|string|max:255',
            'status' => 'required|string|in:Belum Dimulai,Proses,Selesai',
            'bukti_dukung' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        if (Auth::id() != $kegiatan->user_id) {
            abort(403, 'Tidak diizinkan mengedit kegiatan ini');
        }

        $statusMap = [
            'Belum' => 'Belum Dimulai',
            'Proses' => 'Dalam Proses',
            'Selesai' => 'Selesai',
        ];
        $validated['status_penyelesaian'] = $statusMap[$validated['status']] ?? 'Belum Dimulai';
        unset($validated['status']);

        if ($request->hasFile('bukti_dukung')) {
            if ($kegiatan->bukti_dukung && Storage::disk('public')->exists($kegiatan->bukti_dukung)) {
                Storage::disk('public')->delete($kegiatan->bukti_dukung);
            }
            $validated['bukti_dukung'] = $request->file('bukti_dukung')->store('bukti', 'public');
        }

        $kegiatan->update($validated);

        return redirect()->route('pelajar.kegiatan.harian')->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if (Auth::id() != $kegiatan->user_id) {
            abort(403, 'Tidak diizinkan menghapus kegiatan ini');
        }

        if ($kegiatan->bukti_dukung && Storage::disk('public')->exists($kegiatan->bukti_dukung)) {
            Storage::disk('public')->delete($kegiatan->bukti_dukung);
        }

        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
