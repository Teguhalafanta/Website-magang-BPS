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

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama' => 'string|required',
            'nim' => 'string|required|min:10|max:15|unique:mahasiswas',
            'telpon' => 'string|required|min:10|max:13',
            'alamat' => 'string|required'
        ]);

        $mahasiswa = Mahasiswa::create($validateData);

        if ($mahasiswa) {
            return to_route('mahasiswa.index')->with('success', 'Berhasil Menambah Data');
        } else {
            return to_route('mahasiswa.index')->with('failed', 'Gagal Menambah Data');
        }
    }
}
