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
        $jumlahAbsensiHariIni = Presensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        return view('dashboard.admin', compact(
            'jumlahPelajar',
            'jumlahAbsensiHariIni',
            'jumlahKegiatan'
        ));
    }

    public function pelajar()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        // Ambil kegiatan hari ini
        $kegiatanHariIni = Kegiatan::where('tanggal', $tanggalHariIni)->get();

        // Total kegiatan
        $totalKegiatan = Kegiatan::count();

        $jumlahAbsensiHariIni = Presensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        return view('dashboard.pelajar', compact(
            'kegiatanHariIni',
            'totalKegiatan',
            'jumlahAbsensiHariIni',
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

        // Jika tidak ada pelajar, gunakan array kosong
        if (empty($userIds)) {
            $userIds = [0]; // ID yang tidak ada untuk menghindari error
        }

        // Statistik untuk kartu dashboard
        $totalMahasiswa = Pelajar::where('pembimbing_id', $user->id)->count();

        // Kegiatan yang belum dimulai atau dalam proses (pending)
        $pendingBimbingan = Kegiatan::whereIn('user_id', $userIds)
            ->whereIn('status_penyelesaian', ['Belum Dimulai', 'Dalam Proses'])
            ->count();

        // Kegiatan selesai bulan ini
        $selesaiBimbingan = Kegiatan::whereIn('user_id', $userIds)
            ->where('status_penyelesaian', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->count();

        // Jadwal hari ini
        $jadwalHariIni = Kegiatan::whereIn('user_id', $userIds)
            ->whereDate('tanggal', today())
            ->count();

        // Data untuk panel bimbingan pending
        $bimbinganPending = Kegiatan::with(['user.pelajar'])
            ->whereIn('user_id', $userIds)
            ->whereIn('status_penyelesaian', ['Belum Dimulai', 'Dalam Proses'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($kegiatan) {
                // Tambahkan properti mahasiswa untuk kompatibilitas dengan view
                $kegiatan->mahasiswa = (object)[
                    'nama' => $kegiatan->user->pelajar->nama ?? $kegiatan->user->name ?? 'N/A'
                ];
                $kegiatan->topik = $kegiatan->nama_kegiatan;
                return $kegiatan;
            });

        // Data untuk jadwal hari ini
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

        // Daftar mahasiswa bimbingan dengan progress
        $mahasiswaBimbingan = Pelajar::where('pembimbing_id', $user->id)
            ->with('user')
            ->get()
            ->map(function ($pelajar) {
                // Hitung total kegiatan dan yang selesai
                $totalKegiatan = Kegiatan::where('user_id', $pelajar->user_id)->count();
                $kegiatanSelesai = Kegiatan::where('user_id', $pelajar->user_id)
                    ->where('status_penyelesaian', 'Selesai')
                    ->count();

                // Progress berdasarkan kegiatan selesai vs total
                $pelajar->progress = $totalKegiatan > 0
                    ? round(($kegiatanSelesai / $totalKegiatan) * 100)
                    : 0;

                $pelajar->total_bimbingan = $totalKegiatan;
                $pelajar->nim = $pelajar->nisn ?? '-';
                $pelajar->judul_penelitian = 'Program Magang ' . ($pelajar->nama ?? '-');

                return $pelajar;
            });

        return view('pembimbing.dashboard', compact(
            'totalMahasiswa',
            'pendingBimbingan',
            'selesaiBimbingan',
            'jadwalHariIni',
            'bimbinganPending',
            'jadwalToday',
            'mahasiswaBimbingan'
        ));
    }
}
