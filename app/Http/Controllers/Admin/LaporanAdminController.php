<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;

class LaporanAdminController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('user')->get();
        return view('admin.laporan.index', compact('laporans'));
    }
}

