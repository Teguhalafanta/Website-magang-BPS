<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mahasiswa;

class mahasiswaController extends Controller
{
    public function index() 
    {
        $mahasiswas = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswas'));
    }
}
