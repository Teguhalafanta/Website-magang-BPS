<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kegiatan;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $versi = $request->query('versi', 'baru'); // Default ke 'baru'

        // Ambil data statistik
        $jumlahMahasiswa = Mahasiswa::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', Carbon::today())->count();

        // Gunakan tampilan berbeda berdasarkan versi
        if ($versi === 'lama') {
            return view('dashboard', compact(
                'jumlahMahasiswa',
                'jumlahKegiatan',
                'jumlahAbsensiHariIni'
            ));
        }

        return view('dashboard.index', compact(
            'jumlahMahasiswa',
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
