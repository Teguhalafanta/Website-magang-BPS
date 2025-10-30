<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function admin(Request $request)
    {
        $jumlahPelajar = Pelajar::whereIn('status', ['disetujui', 'selesai'])->count();
        $jumlahPresensiHariIni = Presensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        $tahun = $request->get('tahun', date('Y'));

        // --- DAFTAR TAHUN ---
        $daftarTahun = Pelajar::select(DB::raw('YEAR(rencana_mulai) as tahun'))
            ->whereNotNull('rencana_mulai')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        // --- GRAFIK JUMLAH PESERTA MAGANG PER BULAN ---
        $data = Pelajar::select(
            DB::raw('MONTH(rencana_mulai) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereNotNull('rencana_mulai')
            ->whereYear('rencana_mulai', $tahun)
            ->where('status', 'disetujui')
            ->groupBy(DB::raw('MONTH(rencana_mulai)'))
            ->pluck('total', 'bulan')
            ->toArray();

        $dataMagangPerBulan = [];
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        foreach ($bulan as $i => $namaBulan) {
            $dataMagangPerBulan['labels'][] = $namaBulan;
            $dataMagangPerBulan['totals'][] = $data[$i] ?? 0;
        }

        // --- GRAFIK PRESENSI HARIAN (DOUGHNUT) ---
        $hariIni = Carbon::today();

        if ($hariIni->isWeekend()) {
            $sudahPresensi = 0;
            $belumPresensi = 0;
            $totalPeserta = 0;
        } else {
            $totalPeserta = Pelajar::where('status', 'disetujui')
                ->whereDate('rencana_mulai', '<=', $hariIni)
                ->whereDate('rencana_selesai', '>=', $hariIni)
                ->count();

            $sudahPresensi = Presensi::whereDate('tanggal', $hariIni)
                ->distinct('user_id')
                ->count('user_id');

            $belumPresensi = max($totalPeserta - $sudahPresensi, 0);
        }

        $dataPresensiHarian = [
            'totalPeserta' => $totalPeserta,
            'sudahPresensi' => $sudahPresensi,
            'belumPresensi' => $belumPresensi,
        ];

        // --- GRAFIK PESERTA MAGANG PER INSTANSI ---
        $instansi = DB::table('pelajars')
            ->select('asal_institusi as nama_instansi', DB::raw('COUNT(*) as total'))
            ->where('status', 'disetujui')
            ->groupBy('asal_institusi')
            ->get();

        // --- GRAFIK GARIS: TOTAL PESERTA MAGANG PER TAHUN ---
        $dataMagangPerTahun = Pelajar::select(
            DB::raw('YEAR(rencana_mulai) as tahun'),
            DB::raw('COUNT(*) as total')
        )
            ->whereNotNull('rencana_mulai')
            ->where('status', 'disetujui')
            ->groupBy(DB::raw('YEAR(rencana_mulai)'))
            ->orderBy(DB::raw('YEAR(rencana_mulai)'))
            ->pluck('total', 'tahun')
            ->toArray();

        $grafikMagangPerTahun = [
            'labels' => array_keys($dataMagangPerTahun),
            'totals' => array_values($dataMagangPerTahun),
        ];

        // --- GRAFIK TIMELINE PESERTA MAGANG (PERIODE MULAI - SELESAI) ---
        $dataMagangTimeline = Pelajar::whereIn('status', ['disetujui', 'selesai'])
            ->whereYear('rencana_mulai', $tahun)
            ->select('nama', 'rencana_mulai', 'rencana_selesai')
            ->orderBy('rencana_mulai', 'asc')
            ->get();

        return view('dashboard.admin', compact(
            'jumlahPelajar',
            'jumlahPresensiHariIni',
            'jumlahKegiatan',
            'dataMagangPerBulan',
            'daftarTahun',
            'tahun',
            'dataPresensiHarian',
            'instansi',
            'grafikMagangPerTahun',
            'dataMagangTimeline'
        ));
    }

    public function pelajar()
    {
        $user = Auth::user();
        $pelajar = $user->pelajar;

        // Cek apakah pelajar sudah terdaftar
        if (!$pelajar) {
            return redirect()->route('pelajar.profil')->with('warning', 'Silakan lengkapi data profil terlebih dahulu');
        }

        // **PENTING: Cek status magang**
        $isMagangSelesai = $pelajar->status_magang === 'selesai';
        $statusMagang = $pelajar->status;

        // Hitung durasi dan sisa hari magang
        $durasi = null;
        $sisaHari = null;
        $persentaseWaktu = 0;

        if ($pelajar->rencana_mulai && $pelajar->rencana_selesai) {
            $mulai = Carbon::parse($pelajar->rencana_mulai);
            $selesai = Carbon::parse($pelajar->rencana_selesai);
            $today = Carbon::today();

            $durasi = $mulai->diffInDays($selesai);

            if ($today->lt($selesai)) {
                $sisaHari = $today->diffInDays($selesai);
            } else {
                $sisaHari = 0;
            }

            if ($durasi > 0) {
                $hariTerlalui = $mulai->diffInDays($today);
                $persentaseWaktu = min(round(($hariTerlalui / $durasi) * 100), 100);
            }
        }

        // Statistik Presensi
        $jumlahPresensiHariIni = Presensi::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $totalPresensi = Presensi::where('user_id', $user->id)->count();

        // Statistik Kegiatan
        $jumlahKegiatan = Kegiatan::where('user_id', $user->id)->count();

        $kegiatanSelesai = Kegiatan::where('user_id', $user->id)
            ->where('status_penyelesaian', 'Selesai')
            ->count();

        $kegiatanProses = Kegiatan::where('user_id', $user->id)
            ->whereIn('status_penyelesaian', ['Belum Dimulai', 'Dalam Proses'])
            ->count();

        $persentaseKegiatan = $jumlahKegiatan > 0
            ? round(($kegiatanSelesai / $jumlahKegiatan) * 100)
            : 0;

        // Kegiatan terbaru (5 terakhir)
        $kegiatanTerbaru = Kegiatan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Presensi terbaru (5 terakhir)
        $presensiTerbaru = Presensi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Kegiatan hari ini
        $kegiatanHariIni = Kegiatan::where('user_id', $user->id)
            ->whereDate('tanggal', today())
            ->orderBy('tanggal', 'asc')
            ->get();

        // Kegiatan minggu ini
        $kegiatanMingguIni = Kegiatan::where('user_id', $user->id)
            ->whereBetween('tanggal', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();

        // Informasi pembimbing
        $pembimbing = $pelajar->pembimbing;

        return view('dashboard.pelajar', compact(
            'jumlahPresensiHariIni',
            'jumlahKegiatan',
            'totalPresensi',
            'kegiatanSelesai',
            'kegiatanProses',
            'persentaseKegiatan',
            'kegiatanTerbaru',
            'presensiTerbaru',
            'kegiatanHariIni',
            'kegiatanMingguIni',
            'isMagangSelesai',
            'statusMagang',
            'durasi',
            'sisaHari',
            'persentaseWaktu',
            'pembimbing',
            'pelajar'
        ));
    }

    public function pembimbing()
    {
        $user = Auth::user();

        // Ambil semua user_id dari pelajar yang dibimbing pembimbing ini
        $userIds = Pelajar::where('pembimbing_id', $user->pembimbing->id ?? null)
            ->pluck('user_id')
            ->filter()
            ->toArray();

        if (empty($userIds)) {
            $userIds = [0]; // supaya query tidak error ketika kosong
        }

        // Statistik utama
        $totalMahasiswa = Pelajar::where('pembimbing_id', $user->pembimbing->id ?? null)->count();

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

        // Laporan kegiatan terbaru
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

        // Jadwal hari ini
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

        // Progress mahasiswa bimbingan
        $mahasiswaBimbingan = Pelajar::where('pembimbing_id', $user->pembimbing->id ?? null)
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

        // Jumlah kegiatan & presensi
        $jumlahKegiatan = Kegiatan::whereIn('user_id', $userIds)->count();

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
