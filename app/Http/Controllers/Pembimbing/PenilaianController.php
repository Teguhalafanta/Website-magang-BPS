<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penilaian;

class PenilaianController extends Controller
{
    public function index()
    {
        $pembimbingId = Auth::user()->id;

        $penilaians = Penilaian::whereHas('pelajar', function ($q) use ($pembimbingId) {
            $q->where('pembimbing_id', $pembimbingId);
        })->with('pelajar')->get();

        return view('pembimbing.penilaian', compact('penilaians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelajar_id' => 'required|exists:pelajars,id',
            'nilai' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        Penilaian::updateOrCreate(
            ['pelajar_id' => $request->pelajar_id],
            [
                'pembimbing_id' => Auth::user()->id,
                'nilai' => $request->nilai,
                'keterangan' => $request->keterangan,
            ]
        );

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
