<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelajar;
use App\Models\Kegiatan;
use App\Models\Presensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pelajar') {
            return redirect()->route('pelajar.dashboard');
        } elseif ($user->role === 'pembimbing') {
            return redirect()->route('pembimbing.dashboard');
        }

        abort(403, 'Unauthorized');
    }

    public function admin()
    {
        $jumlahPelajar = Pelajar::count();
        $jumlahPresensiHariIni = Presensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        return view('dashboard.admin', compact(
            'jumlahPelajar',
            'jumlahPresensiHariIni',
            'jumlahKegiatan'
        ));
    }

    public function pelajar()
    {
        $jumlahPresensiHariIni = Presensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        return view('dashboard.pelajar', compact(
            'jumlahPresensiHariIni',
            'jumlahKegiatan'
        ));
    }

    public function pembimbing()
    {
        $user = Auth::user();

        // Ambil user_id dari pelajar yang dibimbing
        $userIds = Pelajar::where('pembimbing_id', $user->id)
            ->pluck('user_id')
            ->filter()
            ->toArray();

        if (empty($userIds)) {
            $userIds = [0];
        }

        // Statistik utama
        $totalMahasiswa = Pelajar::where('pembimbing_id', $user->id)->count();
        $pendingBimbingan = Kegiatan::whereIn('user_id', $userIds)
            ->whereIn('status_penyelesaian', ['Belum Dimulai', 'Dalam Proses'])
            ->count();
        $selesaiBimbingan = Kegiatan::whereIn('user_id', $userIds)
            ->where('status_penyelesaian', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->count();
        $jadwalHariIni = Kegiatan::whereIn('user_id', $userIds)
            ->whereDate('tanggal', today())
            ->count();

        // Data kegiatan yang diambil dari controller Kegiatan
        $laporanTerbaru = Kegiatan::with(['user.pelajar'])
            ->whereIn('user_id', $userIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($kegiatan) {
                $kegiatan->mahasiswa = (object)[
                    'nama' => $kegiatan->user->pelajar->nama ?? $kegiatan->user->name ?? 'N/A'
                ];
                $kegiatan->topik = $kegiatan->nama_kegiatan;
                $kegiatan->status = $kegiatan->status_penyelesaian;
                $kegiatan->waktu = Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y');
                return $kegiatan;
            });

        // Data bimbingan pending
        $bimbinganPending = Kegiatan::with(['user.pelajar'])
            ->whereIn('user_id', $userIds)
            ->whereIn('status_penyelesaian', ['Belum Dimulai', 'Dalam Proses'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($kegiatan) {
                $kegiatan->mahasiswa = (object)[
                    'nama' => $kegiatan->user->pelajar->nama ?? $kegiatan->user->name ?? 'N/A'
                ];
                $kegiatan->topik = $kegiatan->nama_kegiatan;
                return $kegiatan;
            });

        // Data jadwal hari ini
        $jadwalToday = Kegiatan::with(['user.pelajar'])
            ->whereIn('user_id', $userIds)
            ->whereDate('tanggal', today())
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get()
            ->map(function ($kegiatan) {
                $kegiatan->mahasiswa = (object)[
                    'nama' => $kegiatan->user->pelajar->nama ?? $kegiatan->user->name ?? 'N/A'
                ];
                $kegiatan->topik = $kegiatan->nama_kegiatan;
                $kegiatan->waktu = $kegiatan->tanggal;
                return $kegiatan;
            });

        // Progress mahasiswa
        $mahasiswaBimbingan = Pelajar::where('pembimbing_id', $user->id)
            ->with('user')
            ->get()
            ->map(function ($pelajar) {
                $totalKegiatan = Kegiatan::where('user_id', $pelajar->user_id)->count();
                $kegiatanSelesai = Kegiatan::where('user_id', $pelajar->user_id)
                    ->where('status_penyelesaian', 'Selesai')
                    ->count();

                $pelajar->progress = $totalKegiatan > 0
                    ? round(($kegiatanSelesai / $totalKegiatan) * 100)
                    : 0;

                $pelajar->total_bimbingan = $totalKegiatan;
                $pelajar->nim = $pelajar->nisn ?? '-';
                $pelajar->judul_penelitian = 'Program Magang ' . ($pelajar->nama ?? '-');

                return $pelajar;
            });

        // Total mahasiswa yang dibimbing
        $totalMahasiswa = Pelajar::where('pembimbing_id', $user->id)->count();

        // Jumlah kegiatan mahasiswa bimbingan
        $jumlahKegiatan = Kegiatan::whereIn('user_id', $userIds)->count();

        // Jumlah presensi hari ini dari mahasiswa bimbingan
        $presensiHariIni = Presensi::whereIn('user_id', $userIds)
            ->whereDate('created_at', today())
            ->count();

        return view('pembimbing.dashboard', compact(
            'totalMahasiswa',
            'pendingBimbingan',
            'selesaiBimbingan',
            'jadwalHariIni',
            'bimbinganPending',
            'jadwalToday',
            'mahasiswaBimbingan',
            'laporanTerbaru',
            'jumlahKegiatan',
            'presensiHariIni'
        ));
    }
}
