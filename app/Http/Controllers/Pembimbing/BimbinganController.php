<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    public function index()
    {
        $pembimbingId = Auth::user()->id;
        $pelajars = Pelajar::where('pembimbing_id', $pembimbingId)->get();

        return view('pembimbing.bimbingan', compact('pelajars'));
    }
}
