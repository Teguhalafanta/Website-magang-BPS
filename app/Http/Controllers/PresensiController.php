<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelajar') {
            $presensis = Presensi::where('pelajar_id', $user->pelajar->id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc')
                ->get();

            return view('presensi.index', compact('presensis'));
        } elseif ($user->role === 'pembimbing') {
            $pembimbing = $user->pembimbing;

            if (!$pembimbing) {
                return view('pembimbing.presensi', [
                    'presensis' => collect(),
                    'pelajars' => collect(),
                ]);
            }

            $pelajars = \App\Models\Pelajar::where('pembimbing_id', $pembimbing->id)->get();

            // Tangkap filter dari request
            $selectedPelajarId = request('pelajar_id');

            $presensisQuery = \App\Models\Presensi::with('pelajar')
                ->whereIn('pelajar_id', $pelajars->pluck('id'))
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc');

            if ($selectedPelajarId) {
                $presensisQuery->where('pelajar_id', $selectedPelajarId);
            }

            $presensis = $presensisQuery->get();

            return view('pembimbing.presensi', compact('presensis', 'pelajars', 'selectedPelajarId'));
        } elseif ($user->role === 'admin') {
            // Admin bisa lihat semua presensi dengan nama pelajar
            $presensis = \App\Models\Presensi::with('pelajar')
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc')
                ->get();

            return view('admin.presensi.index', compact('presensis'));
        } else {
            abort(403); // role lain tidak bisa mengakses
        }
    }

    public function create()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $sudah = Presensi::where('pelajar_id', $user->pelajar->id)
            ->where('tanggal', $today)
            ->exists();

        if ($sudah) {
            return redirect()->route('presensi.index')
                ->with('warning', 'Anda sudah melakukan presensi hari ini!');
        }

        return view('presensi.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        $pelajar = $user->pelajar;

        $exists = Presensi::where('pelajar_id', $pelajar->id)
            ->where('tanggal', $today)
            ->exists();

        if ($exists) {
            return redirect()->route('pelajar.presensi.index')
                ->with('error', 'Anda sudah melakukan presensi hari ini!');
        }

        // Ambil jam dari client, jika kosong pakai server
        $jamDatang = $request->jam_client ?? Carbon::now()->format('H:i:s');
        $batas = '07:35:00';
        $status = $jamDatang > $batas ? 'Terlambat' : 'Tepat Waktu';

        Presensi::create([
            'pelajar_id' => $pelajar->id,
            'user_id' => $user->id,
            'tanggal' => $today,
            'waktu_datang' => $jamDatang,
            'status' => $status,
        ]);

        return redirect()->route('pelajar.presensi.index')
            ->with('success', "Presensi masuk berhasil dicatat pada pukul $jamDatang");
    }

    public function show($id)
    {
        $presensi = Presensi::with('user')->findOrFail($id);

        if (Auth::user()->role === 'pelajar' && $presensi->pelajar_id !== Auth::id()) {
            abort(403);
        }

        return view('presensi.show', compact('presensi'));
    }

    public function edit($id)
    {
        if (Auth::user()->role === 'pelajar') {
            abort(403);
        }

        $presensi = Presensi::findOrFail($id);
        return view('presensi.edit', compact('presensi'));
    }

    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);

        if ($presensi->waktu_pulang) {
            return redirect()->route('pelajar.presensi.index')
                ->with('error', 'Anda sudah melakukan presensi pulang hari ini!');
        }

        // Ambil waktu dari browser, fallback ke server
        $jamPulang = $request->jam_client ?? Carbon::now()->format('H:i:s');

        $presensi->update([
            'waktu_pulang' => $jamPulang,
        ]);

        return redirect()->route('pelajar.presensi.index')
            ->with('success', "Presensi pulang berhasil dicatat pada pukul $jamPulang");
    }


    public function destroy($id)
    {
        if (Auth::user()->role === 'pelajar') {
            abort(403);
        }

        $presensi = Presensi::findOrFail($id);
        $presensi->delete();

        return redirect()->route('presensi.index')
            ->with('success', 'Presensi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $user = Auth::user();
        $bulanParam = $request->input('bulan', Carbon::now()->format('Y-m'));
        $carbon = Carbon::parse($bulanParam);

        $query = Presensi::whereYear('tanggal', $carbon->year)
            ->whereMonth('tanggal', $carbon->month);

        if ($user->role === 'pelajar') {
            $query->where('pelajar_id', $user->id);
        } else {
            $query->with('user');
        }

        $presensis = $query->orderBy('tanggal', 'asc')->get();

        $statistik = [
            'total' => $presensis->count(),
            'tepat_waktu' => $presensis->where('status', 'Tepat Waktu')->count(),
            'terlambat' => $presensis->where('status', 'Terlambat')->count(),
            'izin' => $presensis->where('status', 'Izin')->count(),
            'sakit' => $presensis->where('status', 'Sakit')->count(),
            'alfa' => $presensis->where('status', 'Alfa')->count(),
        ];

        return view('presensi.rekap', compact('presensis', 'statistik', 'bulanParam'));
    }
}
