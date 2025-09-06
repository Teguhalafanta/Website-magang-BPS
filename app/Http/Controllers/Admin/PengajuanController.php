<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;

class PengajuanController extends Controller
{
    public function index()
    {
        // Admin bisa lihat semua pengajuan
        $pengajuans = Pelajar::with('user')->latest()->get();

        return view('admin.pengajuan.daftar', compact('pengajuans'));
    }
}
