<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelajar;
use App\Models\Kegiatan;
use App\Models\Presensi;
use Carbon\Carbon;
use App\Http\Controllers\PengajuanController;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pelajar') {
            $tanggalHariIni = Carbon::today()->toDateString();

            // Ambil kegiatan hari ini
            $kegiatanHariIni = Kegiatan::where('tanggal', $tanggalHariIni)->get();

            // Total kegiatan
            $totalKegiatan = Kegiatan::count();

            return view('pelajar.dashboard', compact('user', 'kegiatanHariIni', 'totalKegiatan'));
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
}
