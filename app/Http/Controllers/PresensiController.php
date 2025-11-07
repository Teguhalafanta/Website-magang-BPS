<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Pelajar;
use Illuminate\Validation\Rule;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelajar') {
            // Cek apakah magang sudah selesai
            $isMagangSelesai = $user->pelajar->status_magang === 'selesai';

            $presensis = Presensi::where('pelajar_id', $user->pelajar->id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc')
                ->get();

            return view('presensi.index', compact('presensis', 'isMagangSelesai'));
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
            // Ambil query dasar
            $query = \App\Models\Presensi::with('pelajar')
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc');

            // Cek apakah ada filter ?today=1
            if (request()->has('today')) {
                $today = Carbon::today()->toDateString();
                $query->whereDate('tanggal', $today);
            }

            $presensis = $query->get();

            return view('admin.presensi.index', compact('presensis'));
        } else {
            abort(403); // role lain tidak bisa mengakses
        }
    }

    public function create()
    {
        $user = Auth::user();

        // PEMBATASAN: Cek apakah magang sudah selesai
        if ($user->pelajar && $user->pelajar->status_magang === 'selesai') {
            return redirect()->route('pelajar.presensi.index')
                ->with('error', 'Magang Anda sudah selesai. Tidak dapat menambahkan presensi baru.');
        }

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

        // PEMBATASAN: Cek apakah magang sudah selesai
        if ($user->pelajar && $user->pelajar->status_magang === 'selesai') {
            return redirect()->route('pelajar.presensi.index')
                ->with('error', 'Magang Anda sudah selesai. Tidak dapat menambahkan presensi baru.');
        }

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
        $batas = '07:45:00';
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
        $user = Auth::user();

        // PEMBATASAN: Cek apakah magang sudah selesai (untuk presensi pulang)
        if ($user->role === 'pelajar' && $user->pelajar && $user->pelajar->status_magang === 'selesai') {
            return redirect()->route('pelajar.presensi.index')
                ->with('error', 'Magang Anda sudah selesai. Tidak dapat melakukan presensi pulang.');
        }

        $presensi = Presensi::where('id', $id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->firstOrFail();

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
    // Tambahkan di PresensiController.php

    public function getPresensiData($id)
    {
        try {
            $presensi = Presensi::findOrFail($id);
            $pelajar = Pelajar::findOrFail($presensi->pelajar_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $presensi->id,
                    'pelajar_nama' => $pelajar->nama,
                    'tanggal' => \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y'),
                    'status' => $presensi->status,
                    'keterangan' => $presensi->keterangan ?? '-'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data presensi tidak ditemukan'
            ], 404);
        }
    }

    public function updatePresensi(Request $request, $id)
    {
        // Validasi hanya untuk pembimbing
        if (Auth::user()->role !== 'pembimbing') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha,Terlambat,Tepat Waktu',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'status.required' => 'Status presensi wajib dipilih',
            'status.in' => 'Status presensi tidak valid',
            'keterangan.max' => 'Keterangan maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $presensi = Presensi::findOrFail($id);

            // Cek apakah presensi ini milik peserta bimbingan pembimbing yang login
            $pembimbing = Auth::user()->pembimbing;
            $pelajar = $presensi->pelajar;

            if ($pelajar->pembimbing_id !== $pembimbing->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah presensi ini'
                ], 403);
            }

            $presensi->status = $request->status;
            $presensi->keterangan = $request->keterangan ?? null;
            $presensi->save();

            return response()->json([
                'success' => true,
                'message' => 'Status presensi berhasil diperbarui',
                'data' => [
                    'id' => $presensi->id,
                    'status' => $presensi->status,
                    'keterangan' => $presensi->keterangan ?? '-'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
