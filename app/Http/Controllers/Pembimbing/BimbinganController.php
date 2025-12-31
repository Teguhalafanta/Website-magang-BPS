<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    public function index()
    {
        $pembimbingId = Auth::user()->pembimbing->id;
        $pelajars = Pelajar::where('pembimbing_id', $pembimbingId)->get();

        // Update status_magang jika tanggal selesai sudah lewat
        foreach ($pelajars as $pelajar) {
            if ($pelajar->rencana_selesai && \Carbon\Carbon::parse($pelajar->rencana_selesai)->isPast() && $pelajar->status_magang !== 'selesai') {
                $pelajar->update(['status_magang' => 'selesai']);
            }
        }

        return view('pembimbing.bimbingan', compact('pelajars'));
    }
}
