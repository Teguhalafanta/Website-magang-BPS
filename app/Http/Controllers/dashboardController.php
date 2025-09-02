<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelajar;
use App\Models\Kegiatan;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $versi = $request->query('versi', 'baru'); // Default ke 'baru'

        // Ambil data statistik
        $jumlahPelajar = Pelajar::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', date('Y-m-d'))->count();

        if ($versi === 'lama') {
            return view('dashboard', compact(
                'jumlahPelajar',
                'jumlahKegiatan',
                'jumlahAbsensiHariIni'
            ));
        }

        return view('dashboard.index', compact(
            'jumlahPelajar',
            'jumlahKegiatan',
            'jumlahAbsensiHariIni'
        ));
    }

    /** Profile user */
    public function userProfile()
    {
        return view('dashboard.profile');
    }
}
