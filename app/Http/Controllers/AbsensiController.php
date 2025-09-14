<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NotifikasiBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pelajar;
use Illuminate\Pagination\LengthAwarePaginator;

class AbsensiController extends Controller
{
    /**
     * Tampilkan daftar absensi, khusus untuk pelajar yang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Ambil pelajar yang terkait user login
        $pelajar = Pelajar::where('id_user', $user->id)->first();

        if (!$pelajar) {
            // Fallback kosong agar tidak error saat memanggil ->links() di Blade
            $absensis = new LengthAwarePaginator([], 0, 10);
            $pelajar_id = null;
        } else {
            $pelajar_id = $pelajar->id_pelajar;

            if ($request->has('today')) {
                $absensis = Absensi::with('pelajar')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->where('pelajar_id', $pelajar_id)
                    ->paginate(10);
            } else {
                $absensis = Absensi::with('pelajar')
                    ->where('pelajar_id', $pelajar_id)
                    ->paginate(10);
            }
        }

        // Data tambahan untuk dashboard
        $jumlahPelajar = Pelajar::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('tanggal', date('Y-m-d'))->count();

        // Semua pelajar (jika dibutuhkan untuk form tambah absensi)
        $pelajars = Pelajar::all();

        // Cek apakah pelajar sudah absen hari ini  
        $absenHariIni = $pelajar_id
            ? Absensi::where('pelajar_id', $pelajar_id)
                ->whereDate('tanggal', date('Y-m-d'))
                ->exists()
            : false;

        return view('absensi.index', compact(
            'absensis',
            'pelajars',
            'absenHariIni',
            'jumlahPelajar',
            'jumlahKegiatan',
            'jumlahAbsensiHariIni'
        ));
    }

    /**
     * Simpan data absensi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelajar_id' => 'required|exists:pelajars,id_pelajar',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi = Absensi::create($validated);

        // Kirim notifikasi ke semua Admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifikasiBaru(
                'Ada absensi baru masuk dari pelajar ID: ' . $absensi->pelajar_id,
                route('absensi.index')
            ));
        }

        // Kirim notifikasi ke User yang login 
        $user = Auth::user(); 
        // DEBUG DI SINI 
        dd($user, get_class($user)); 
        if ($user) { 
            $user->notify(new NotifikasiBaru( 
                'Absensi kamu berhasil disimpan!', 
                route('absensi.index') 
            )); 
        }

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit absensi.
     */
    public function edit($id)
    {
        $absen = Absensi::findOrFail($id);
        $pelajars = Pelajar::all();

        return view('absensi.edit', compact('absen', 'pelajars'));
    }

    /**
     * Update data absensi.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pelajar_id' => 'required|exists:pelajars,id_pelajar',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update($validated);

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Hapus data absensi.
     */
    public function destroy($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
