<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelajar;
use App\Models\Kegiatan;
use App\Models\Absensi;
use App\Http\Controllers\PengajuanController;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pelajar') {
            return redirect()->route('pelajar.dashboard');
        }

        abort(403, 'Unauthorized');
    }

    public function admin()
    {
        $jumlahPelajar = Pelajar::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        return view('dashboard.admin', compact(
            'jumlahPelajar',
            'jumlahAbsensiHariIni',
            'jumlahKegiatan'
        ));
    }

    public function pelajar()
    {
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', now())->count();
        $jumlahKegiatan = Kegiatan::count();

        return view('dashboard.pelajar', compact(
            'jumlahAbsensiHariIni',
            'jumlahKegiatan'
        ));
    }
}
