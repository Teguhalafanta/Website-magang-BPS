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
            // PEMBATASAN: Cek apakah magang sudah selesai
            $isMagangSelesai = $user->pelajar && $user->pelajar->status_magang === 'selesai';

            $kegiatans = Kegiatan::where('user_id', $user->id)
                ->latest()
                ->paginate(10);

            return view('kegiatan.index', compact('kegiatans', 'isMagangSelesai'));
        }

        if ($user->role == 'pembimbing') {
            // Ambil ID pembimbing dari tabel pembimbings
            $pembimbing = $user->pembimbing ?? null;
            if (!$pembimbing) {
                return view('pembimbing.kegiatan', ['kegiatans' => collect()]);
            }

            // Ambil semua pelajar yang dibimbing oleh pembimbing ini
            $pelajarIds = Pelajar::where('pembimbing_id', $pembimbing->id)->pluck('user_id');

            // Ambil kegiatan yang dimiliki oleh pelajar-pelajar tersebut
            $kegiatans = Kegiatan::whereIn('user_id', $pelajarIds)
                ->when($request->search, function ($query) use ($request) {
                    $query->where('nama_kegiatan', 'like', "%{$request->search}%")
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        });
                })
                ->with('pelajar')
                ->latest()
                ->paginate(10);

            return view('pembimbing.kegiatan', compact('kegiatans'));
        }


        // Jika bukan pelajar/pembimbing
        abort(403, 'Akses tidak diizinkan');
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();

        if ($user->role != 'admin') {
            abort(403, 'Akses tidak diizinkan');
        }

        $kegiatans = Kegiatan::with('pelajar')
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_kegiatan', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('admin.kegiatan.index', compact('kegiatans'));
    }

    public function harian()
    {
        $user = Auth::user();
        $pelajar = $user->pelajar;

        // Default: magang belum selesai
        $isMagangSelesai = false;

        if ($pelajar) {
            // Ambil tanggal selesai magang
            $tanggalSelesai = Carbon::parse($pelajar->rencana_selesai);

            // Jika hari ini >= tanggal selesai, ubah otomatis statusnya
            if (now()->greaterThanOrEqualTo($tanggalSelesai)) {
                $pelajar->update(['status_magang' => 'selesai']);
                $isMagangSelesai = true;
            } elseif ($pelajar->status_magang === 'selesai') {
                $isMagangSelesai = true;
            }
        }

        $today = now()->format('Y-m-d');

        $kegiatans = Kegiatan::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->get();

        return view('kegiatan.harian', compact('kegiatans', 'isMagangSelesai'));
    }


    public function kegiatanBulanan(Request $request)
    {
        $user = Auth::user();

        // PEMBATASAN: Cek apakah magang sudah selesai
        $isMagangSelesai = $user->pelajar && $user->pelajar->status_magang === 'selesai';

        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        [$tahun, $bulanNum] = explode('-', $bulan);

        $kegiatans = Kegiatan::where('user_id', Auth::id())
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->get();

        return view('kegiatan.bulanan', compact('kegiatans', 'bulan', 'isMagangSelesai'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pelajar = $user->pelajar;
        if ($pelajar) {
            $tanggalSelesai = Carbon::parse($pelajar->rencana_selesai);
            if (now()->greaterThanOrEqualTo($tanggalSelesai)) {
                $pelajar->update(['status_magang' => 'selesai']);
                return redirect()->route('pelajar.kegiatan.harian')
                    ->with('error', 'Magang Anda sudah selesai. Tidak dapat menambahkan kegiatan baru.');
            }
        }


        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
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
        $user = Auth::user();

        // PEMBATASAN: Cek apakah magang sudah selesai
        if ($user->pelajar && $user->pelajar->status_magang === 'selesai') {
            return redirect()->route('pelajar.kegiatan.harian')
                ->with('error', 'Magang Anda sudah selesai. Tidak dapat mengedit kegiatan.');
        }

        $kegiatan = Kegiatan::findOrFail($id);

        if (Auth::id() != $kegiatan->user_id) {
            abort(403, 'Tidak diizinkan mengedit kegiatan ini');
        }

        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // PEMBATASAN: Cek apakah magang sudah selesai
        if ($user->pelajar && $user->pelajar->status_magang === 'selesai') {
            return redirect()->route('pelajar.kegiatan.harian')
                ->with('error', 'Magang Anda sudah selesai. Tidak dapat mengedit kegiatan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
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
        $user = Auth::user();

        // PEMBATASAN: Cek apakah magang sudah selesai
        if ($user->pelajar && $user->pelajar->status_magang === 'selesai') {
            return redirect()->route('pelajar.kegiatan.harian')
                ->with('error', 'Magang Anda sudah selesai. Tidak dapat menghapus kegiatan.');
        }

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
