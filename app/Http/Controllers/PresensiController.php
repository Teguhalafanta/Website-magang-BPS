<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Presensi;
use App\Models\Kegiatan;
use App\Models\Pelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotifikasiBaru;
use Illuminate\Pagination\LengthAwarePaginator;

class PresensiController extends Controller
{
    /**
     * ===============================
     *  BAGIAN UNTUK PELAJAR
     * ===============================
     */

    /**
     * Tampilkan daftar presensi pelajar yang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Jika user adalah pembimbing, arahkan ke fungsi khusus pembimbing
        if ($user->role === 'pembimbing') {
            return $this->indexPembimbing($request);
        }

        // Ambil pelajar via relasi User -> Pelajar
        $pelajar = $user->pelajar;

        if (!$pelajar) {
            // fallback agar tidak error di Blade
            $presensis = new LengthAwarePaginator([], 0, 10);
            $pelajar_id = null;
        } else {
            $pelajar_id = $pelajar->id;

            $query = Presensi::with('pelajar')->where('pelajar_id', $pelajar_id);

            if ($request->has('today')) {
                $query->whereDate('tanggal', date('Y-m-d'));
            }

            $presensis = $query->orderBy('tanggal', 'desc')->paginate(10);
        }

        // Data tambahan (opsional)
        $jumlahPelajar = Pelajar::count();
        $jumlahKegiatan = Kegiatan::count();
        $jumlahPresensiHariIni = Presensi::whereDate('tanggal', date('Y-m-d'))->count();

        $pelajars = Pelajar::all();

        $presensiHariIni = $pelajar_id
            ? Presensi::where('pelajar_id', $pelajar_id)
            ->whereDate('tanggal', date('Y-m-d'))
            ->exists()
            : false;

        return view('presensi.index', compact(
            'presensis',
            'pelajars',
            'presensiHariIni',
            'jumlahPelajar',
            'jumlahKegiatan',
            'jumlahPresensiHariIni'
        ));
    }

    /**
     * Simpan data presensi baru (pelajar).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelajar_id' => 'required|exists:pelajars,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alpha',
            'shift'      => 'required|in:Pagi,Siang',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek duplikasi
        $exists = Presensi::where('pelajar_id', $validated['pelajar_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('shift', $validated['shift'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['shift' => 'Presensi untuk shift ini sudah ada di tanggal tersebut.'])->withInput();
        }

        $presensi = Presensi::create($validated);

        // Kirim notifikasi ke admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifikasiBaru(
                'Ada presensi baru masuk dari pelajar ID: ' . $presensi->pelajar_id,
                route('presensi.index')
            ));
        }

        // Kirim notifikasi ke user (pelajar) yang login
        $user = Auth::user();
        if ($user) {
            $user->notify(new NotifikasiBaru(
                'Presensi kamu berhasil disimpan!',
                route('presensi.index')
            ));
        }

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil ditambahkan.');
    }

    /**
     * Form edit presensi.
     */
    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        $pelajars = Pelajar::all();

        return view('presensi.edit', compact('presensi', 'pelajars'));
    }

    /**
     * Update data presensi (pelajar).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pelajar_id' => 'required|exists:pelajars,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $presensi = Presensi::findOrFail($id);
        $presensi->update($validated);

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil diperbarui.');
    }

    /**
     * Hapus data presensi.
     */
    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil dihapus.');
    }


    /**
     * ===============================
     *  BAGIAN UNTUK PEMBIMBING
     * ===============================
     */

    /**
     * Tampilkan daftar presensi pelajar yang dibimbing pembimbing yang login.
     */
    public function indexPembimbing(Request $request)
    {
        $user = Auth::user();

        $pembimbing = $user->pembimbing;

        if (!$pembimbing) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki pelajar yang dibimbing.');
        }

        // Ambil presensi pelajar yang dibimbing pembimbing
        $presensis = Presensi::whereHas('pelajar', function ($query) use ($pembimbing) {
            $query->where('pembimbing_id', $pembimbing->id);
        })->orderBy('tanggal', 'desc')->paginate(10);

        return view('pembimbing.presensi', compact('presensis'));
    }

    /**
     * Tampilkan detail presensi (opsional untuk pembimbing).
     */
    public function showPembimbing($id)
    {
        $presensi = Presensi::findOrFail($id);
        return view('pembimbing.presensi.show', compact('presensi'));
    }
}
