<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Pelajar;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelajar') {
            // Guard: pastikan relasi pelajar ada
            if (!$user->pelajar) {
                Log::warning('PresensiController@index: authenticated user has no pelajar relation', ['user_id' => $user->id]);
                abort(403, 'Akun Anda belum memiliki data pelajar. Hubungi administrator.');
            }

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
                    'allDates' => collect(),
                    'startDate' => null,
                    'endDate' => null,
                ]);
            }

            $pelajars = \App\Models\Pelajar::where('pembimbing_id', $pembimbing->id)->get();

            // Tangkap filter dari request
            $selectedPelajarId = request('pelajar_id');

            // Generate semua tanggal periode magang
            $allDates = collect();
            $startDate = null;
            $endDate = null;

            if ($pelajars->isNotEmpty()) {
                // Ambil tanggal mulai dan selesai dari pelajar (sesuaikan dengan struktur database Anda)
                $startDate = $pelajars->first()->tanggal_mulai ?? now()->subMonths(3)->format('Y-m-d');
                $endDate = $pelajars->first()->tanggal_selesai ?? now()->format('Y-m-d');

                // Jika ada pelajar yang dipilih, gunakan tanggal dari pelajar tersebut
                if ($selectedPelajarId) {
                    $selectedPelajar = \App\Models\Pelajar::find($selectedPelajarId);
                    if ($selectedPelajar) {
                        $startDate = $selectedPelajar->tanggal_mulai ?? $startDate;
                        $endDate = $selectedPelajar->tanggal_selesai ?? $endDate;
                    }
                }

                // Generate semua tanggal antara start dan end date
                $current = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);

                while ($current <= $end) {
                    // Hanya hari kerja (Senin-Jumat), sesuaikan jika perlu
                    if ($current->isWeekday()) {
                        $allDates->push($current->format('Y-m-d'));
                    }
                    $current->addDay();
                }

                // Reverse dates untuk urutan descending (terbaru di depan)
                $allDates = $allDates->reverse();
            }

            $presensisQuery = \App\Models\Presensi::with('pelajar')
                ->whereIn('pelajar_id', $pelajars->pluck('id'))
                ->orderBy('tanggal', 'desc')
                ->orderBy('waktu_datang', 'desc');

            if ($selectedPelajarId) {
                $presensisQuery->where('pelajar_id', $selectedPelajarId);
            }

            $presensis = $presensisQuery->get();

            // Group presensi untuk memudahkan akses di view
            $presensiGrouped = $presensis->groupBy(['pelajar_id', function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            }]);

            return view('pembimbing.presensi', compact(
                'presensis',
                'pelajars',
                'selectedPelajarId',
                'allDates',
                'presensiGrouped',
                'startDate',
                'endDate'
            ));
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

            // Filter by pelajar jika ada
            if (request()->has('pelajar_id')) {
                $query->where('pelajar_id', request('pelajar_id'));
            }

            $presensis = $query->get();

            // Untuk admin, tampilkan semua pelajar
            $allPelajars = \App\Models\Pelajar::all();

            // Generate all dates untuk admin view (opsional)
            $allDates = collect();
            $startDate = now()->subMonths(3)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');

            $current = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            while ($current <= $end) {
                if ($current->isWeekday()) {
                    $allDates->push($current->format('Y-m-d'));
                }
                $current->addDay();
            }

            $allDates = $allDates->reverse();

            $presensiGrouped = $presensis->groupBy(['pelajar_id', function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            }]);

            return view('admin.presensi.index', compact(
                'presensis',
                'allPelajars',
                'allDates',
                'presensiGrouped',
                'startDate',
                'endDate'
            ));
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

        // Guard: pastikan user memiliki relasi pelajar
        if (!$user->pelajar) {
            Log::warning('PresensiController@store: attempt to store presensi but user has no pelajar relation', ['user_id' => $user->id, 'request' => $request->all()]);
            return redirect()->route('pelajar.dashboard')->with('error', 'Akun Anda belum terdaftar sebagai pelajar.');
        }

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

        // Untuk aksi pulang oleh pelajar, cari berdasarkan pelajar_id dan tanggal
        // (jangan mengikat ke user_id karena presensi bisa dibuat oleh pembimbing)
        if ($user->role === 'pelajar') {
            if (!$user->pelajar) {
                Log::warning('PresensiController@update: user has no pelajar relation', ['user_id' => $user->id]);
                return redirect()->route('pelajar.dashboard')->with('error', 'Akun Anda belum terdaftar sebagai pelajar.');
            }

            $today = Carbon::today()->toDateString();

            $presensi = Presensi::where('id', $id)
                ->where('pelajar_id', $user->pelajar->id)
                ->where('tanggal', $today)
                ->firstOrFail();
        } else {
            // Untuk non-pelajar (should not reach here normally) fall back to previous strict check
            $presensi = Presensi::where('id', $id)
                ->where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->firstOrFail();
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

    /**
     * Store a new presensi record created by a pembimbing (via AJAX).
     * This endpoint is for pembimbing role only and returns JSON.
     */
    public function storeByPembimbing(Request $request)
    {
        if (Auth::user()->role !== 'pembimbing') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'pelajar_id' => 'required|exists:pelajars,id',
            'tanggal' => 'required|date',
            'status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alpha', 'terlambat', 'tepat waktu', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Terlambat', 'Tepat Waktu'])],
            'keterangan' => 'nullable|string|max:500'
        ], [
            'pelajar_id.required' => 'Pelajar harus dipilih',
            'pelajar_id.exists' => 'Pelajar tidak ditemukan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'status.required' => 'Status wajib dipilih'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pembimbing = Auth::user()->pembimbing;
            $pelajar = Pelajar::findOrFail($request->pelajar_id);

            if ($pelajar->pembimbing_id !== $pembimbing->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menambah presensi untuk pelajar ini'
                ], 403);
            }

            // Normalize status similar to updatePresensi
            $inputStatus = $request->input('status');
            $normalized = mb_strtolower(trim($inputStatus));
            if ($normalized === 'alfa') {
                $normalized = 'alpha';
            }
            $normalized = str_replace(['_', "\u00A0"], [' ', ' '], $normalized);
            $normalized = preg_replace('/\s+/', ' ', $normalized);
            if ($normalized === 'tepatwaktu') {
                $normalized = 'tepat waktu';
            }

            $dbStatusMap = [
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha' => 'Alpha',
                'terlambat' => 'Terlambat',
                'tepat waktu' => 'Tepat Waktu'
            ];
            $dbStatus = $dbStatusMap[$normalized] ?? ucfirst($normalized);

            // Prevent duplicate presensi for same pelajar+tanggal
            $exists = Presensi::where('pelajar_id', $pelajar->id)
                ->where('tanggal', Carbon::parse($request->tanggal)->toDateString())
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Presensi untuk tanggal tersebut sudah ada'
                ], 409);
            }

            $presensi = Presensi::create([
                'pelajar_id' => $pelajar->id,
                'user_id' => Auth::id(),
                'tanggal' => Carbon::parse($request->tanggal)->toDateString(),
                'waktu_datang' => $request->waktu_datang ?? null,
                'status' => $dbStatus,
                'keterangan' => $request->keterangan ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil ditambahkan',
                'data' => [
                    'id' => $presensi->id,
                    'status' => $presensi->status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('storeByPembimbing failed', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan presensi'
            ], 500);
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

        // Normalisasi status agar cocok dengan nilai enum di database.
        // Beberapa bagian aplikasi menggunakan variasi kapitalisasi ("Izin" vs "izin").
        // Kita ubah ke format canonical lowercase yang digunakan di view/controller lain.
        $inputStatus = $request->input('status', '');
        $normalized = mb_strtolower(trim($inputStatus));

        // Normalisasi beberapa variasi penulisan
        if ($normalized === 'alfa') {
            $normalized = 'alpha';
        }
        // 'tepatwaktu' atau 'tepat_waktu' => 'tepat waktu'
        $normalized = str_replace(['_', "\u00A0"], [' ', ' '], $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        // Jika ada input tanpa spasi untuk 'tepatwaktu'
        if ($normalized === 'tepatwaktu') {
            $normalized = 'tepat waktu';
        }

        // Ganti nilai request sementara untuk validasi berikutnya
        $dataForValidation = array_merge($request->all(), ['status' => $normalized]);

        $validator = Validator::make($dataForValidation, [
            // Sesuaikan rule dengan format yang disimpan di DB (lowercase)
            'status' => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alpha', 'terlambat', 'tepat waktu'])],
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

            // Map nilai ter-normalisasi kembali ke format enum yang disimpan di database
            $dbStatusMap = [
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha' => 'Alpha',
                'terlambat' => 'Terlambat',
                'tepat waktu' => 'Tepat Waktu'
            ];

            $dbStatus = $dbStatusMap[$normalized] ?? ucfirst($normalized);

            // Simpan ke model menggunakan label enum yang sesuai
            // Tambahkan logging untuk debug jika DB mengembalikan peringatan enum
            Log::info('Presensi update attempt', [
                'presensi_id' => $presensi->id,
                'dbStatus' => $dbStatus,
                'dbStatus_type' => gettype($dbStatus),
            ]);

            $presensi->status = (string) $dbStatus;
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
            // Log detail lengkap untuk membantu diagnosa enum/DB issue
            try {
                // DB::select returns an array of stdClass objects
                $pdoInfo = DB::select("SELECT @@sql_mode AS sql_mode");
            } catch (\Exception $ex) {
                $pdoInfo = null;
            }

            // Safely extract sql_mode whether $pdoInfo is an array of objects or null
            $sqlMode = null;
            if (is_array($pdoInfo) && isset($pdoInfo[0])) {
                $row = $pdoInfo[0];
                if (is_object($row)) {
                    $sqlMode = property_exists($row, 'sql_mode') ? $row->sql_mode : null;
                } elseif (is_array($row)) {
                    $sqlMode = $row['sql_mode'] ?? null;
                }
            }

            // Also attempt to fetch the CREATE TABLE statement for presensis to inspect enum
            $createTable = null;
            try {
                $res = DB::select("SHOW CREATE TABLE presensis");
                if (is_array($res) && isset($res[0])) {
                    $r0 = $res[0];
                    // The returned object usually has property 'Create Table'
                    if (is_object($r0) && property_exists($r0, 'Create Table')) {
                        $createTable = $r0->{'Create Table'};
                    } elseif (is_object($r0)) {
                        // fallback: cast to array and try keys
                        $arr = (array) $r0;
                        $createTable = reset($arr) ?: null;
                    }
                }
            } catch (\Exception $ex) {
                $createTable = null;
            }

            Log::error('Presensi update failed', [
                'exception' => $e->getMessage(),
                'presensi_id' => $id,
                'attempted_dbStatus' => $dbStatus ?? null,
                'raw_status_input' => $request->status ?? null,
                'normalized_status' => $normalized ?? null,
                'request_all' => $request->all(),
                'sql_mode' => $sqlMode,
                'create_table' => $createTable,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
