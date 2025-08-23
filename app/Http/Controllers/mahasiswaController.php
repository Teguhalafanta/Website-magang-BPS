<?php
// app/Http/Controllers/MahasiswaController.php
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller {
    public function index() {
        $mahasiswas = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama'=>'required',
            'nim'=>'required|unique:mahasiswas',
            'telepon'=>'required',
            'alamat'=>'required'
        ]);
        Mahasiswa::create($request->all());
        return redirect()->back()->with('success','Data mahasiswa berhasil ditambahkan');
    }

    // 🆕 Tambahan: Form edit mahasiswa
    public function edit(Mahasiswa $mahasiswa) {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // 🆕 Tambahan: Update data mahasiswa
    public function update(Request $request, Mahasiswa $mahasiswa) {
        $request->validate([
            'nama'=>'required',
            'nim'=>'required|unique:mahasiswas,nim,'.$mahasiswa->id,
            'telepon'=>'required',
            'alamat'=>'required'
        ]);
        $mahasiswa->update($request->all());
        return redirect()->route('mahasiswa.index')->with('success','Data mahasiswa berhasil diperbarui');
    }

    // 🆕 Tambahan: Hapus mahasiswa
    public function destroy(Mahasiswa $mahasiswa) {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success','Data mahasiswa berhasil dihapus');
    }
}
