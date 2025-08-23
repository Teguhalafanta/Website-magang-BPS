<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Absensi;
use App\Models\Kegiatan;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan halaman utama mahasiswa + absensi.
     */
    public function index()
    {
        $mahasiswaId = Auth::id();
        $today = now()->toDateString();

        $kegiatan = Kegiatan::with('mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderByDesc('tanggal')
            ->get();

        $absensis = Absensi::with('mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderByDesc('created_at')
            ->get();

        $absenHariIni = Absensi::where('mahasiswa_id', $mahasiswaId)
            ->where('tanggal', $today)
            ->exists();

        // Tambahkan data semua mahasiswa
        $mahasiswas = Mahasiswa::all();

        return view('mahasiswa.index', compact('kegiatan', 'absensis', 'absenHariIni', 'mahasiswas'));
    }

    /**
     * Simpan data mahasiswa baru (dan absensi jika ada).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'nim'    => 'required|string|max:20|unique:mahasiswas,nim',
            'telpon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'status' => 'nullable|in:hadir,tidak_hadir,izin,sakit',
        ]);

        $mahasiswa = Mahasiswa::create([
            'nama'   => $request->nama,
            'nim'    => $request->nim,
            'telpon' => $request->telpon,
            'alamat' => $request->alamat,
        ]);

        if ($request->filled('status')) {
            Absensi::create([
                'mahasiswa_id'    => $mahasiswa->id,
                'status'          => $request->status,
                'nama_mahasiswa'  => $mahasiswa->nama,
                'tanggal'         => now()->format('Y-m-d'),
                'waktu'           => now()->format('H:i:s'),
            ]);
        }

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail mahasiswa.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Tampilkan form edit mahasiswa.
     */
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update data mahasiswa.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama'   => 'required|string|max:255',
            'nim'    => 'required|string|max:20|unique:mahasiswas,nim,' . $id,
            'telpon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
        ]);

        $mahasiswa->update([
            'nama'   => $request->nama,
            'nim'    => $request->nim,
            'telpon' => $request->telpon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Hapus mahasiswa dan absensi terkait.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        Absensi::where('mahasiswa_id', $id)->delete();
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * Ambil data mahasiswa (untuk kebutuhan AJAX).
     */
    public function getMahasiswaData()
    {
        $mahasiswas = Mahasiswa::with('absensi')->get();

        return response()->json([
            'status' => 'success',
            'data' => $mahasiswas
        ]);
    }

    /**
     * Cari mahasiswa berdasarkan nama atau NIM.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $mahasiswas = Mahasiswa::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('nim', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $mahasiswas
        ]);
    }
}
