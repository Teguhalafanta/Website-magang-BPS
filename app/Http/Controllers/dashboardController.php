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
        $versi = $request->query('versi', 'baru'); // default ke 'baru'

        // Ambil data statistik
        $jumlahMahasiswa = Mahasiswa::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('created_at', Carbon::today())->count();

        // Gunakan tampilan berbeda berdasarkan versi
        if ($versi === 'lama') {
            return view('dashboard', [
                'jumlahMahasiswa' => $jumlahMahasiswa,
                'jumlahKegiatan' => $jumlahKegiatan,
                'jumlahAbsensiHariIni' => $jumlahAbsensiHariIni,
            ]);
        } else {
            return view('dashboard.index', [
                'jumlahMahasiswa' => $jumlahMahasiswa,
                'jumlahKegiatan' => $jumlahKegiatan,
                'jumlahAbsensiHariIni' => $jumlahAbsensiHariIni,
            ]);
        }
    }

    /** profile user */
    public function userProfile()
    {
        return view('dashboard.profile');
    }
}
