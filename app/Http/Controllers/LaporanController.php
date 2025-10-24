<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::where('user_id', Auth::id())->first();
        return view('pelajar.laporan.index', compact('laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        // Hapus laporan lama (jika ada)
        $old = Laporan::where('user_id', Auth::id())->first();
        if ($old && Storage::exists($old->file)) {
            Storage::delete($old->file);
            $old->delete();
        }

        // Simpan file baru
        $path = $request->file('file')->store('laporan', 'public');

        Laporan::create([
            'user_id' => Auth::id(),
            'file' => $path,
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diunggah!');
    }
}
