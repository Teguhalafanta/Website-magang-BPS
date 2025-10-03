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
            $presensis = Presensi::where('user_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc')
                ->get();
        } else {
            $presensis = Presensi::with('user')
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc')
                ->get();
        }

        return view('presensi.index', compact('presensis'));
    }

    public function create()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $sudah = Presensi::where('user_id', $user->id)
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
        $now = Carbon::now();
        $today = $now->toDateString();

        // Cek apakah sudah presensi
        $exists = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->exists();
        if ($exists) {
            return redirect()->route('presensi.index')
                ->with('error', 'Anda sudah melakukan presensi hari ini!');
        }

        // Validasi (tergantung apakah ada input tambahan)
        $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Tentukan status otomatis berdasarkan jam
        $jamDatang = $now->format('H:i:s');
        $batas = '07:35:00';
        $status = $jamDatang > $batas ? 'Terlambat' : 'Tepat Waktu';

        Presensi::create([
            'tanggal' => $today,
            'waktu_datang' => $jamDatang,
            'status' => $status,
        ]);

        return redirect()->route('presensi.index')
            ->with('success', 'Presensi berhasil disimpan. Status: ' . $status);
    }

    public function show($id)
    {
        $presensi = Presensi::with('user')->findOrFail($id);

        if (Auth::user()->role === 'pelajar' && $presensi->user_id !== Auth::id()) {
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
        if (Auth::user()->role === 'pelajar') {
            abort(403);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'waktu_datang' => 'required|date_format:H:i:s',
            'waktu_pulang' => 'nullable|date_format:H:i:s',
            'status' => ['required', Rule::in(['Tepat Waktu', 'Terlambat', 'Izin', 'Sakit', 'Alfa'])],
            'keterangan' => 'nullable|string|max:500',
        ]);

        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'tanggal' => $request->tanggal,
            'waktu_datang' => $request->waktu_datang,
            'waktu_pulang' => $request->waktu_pulang,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('presensi.index')
            ->with('success', 'Presensi berhasil diperbarui.');
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
            $query->where('user_id', $user->id);
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

    }
}
