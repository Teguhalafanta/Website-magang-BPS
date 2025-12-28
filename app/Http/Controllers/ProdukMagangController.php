<?php

namespace App\Http\Controllers;

use App\Models\ProdukMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukMagangController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pelajar') {
            $produk = ProdukMagang::where('pelajar_id', $user->pelajar->id)->get();
            return view('pelajar.produk.index', compact('produk'));
        }

        if ($user->role === 'pembimbing') {
            $produk = ProdukMagang::whereHas('pelajar', function ($q) use ($user) {
                $q->where('pembimbing_id', $user->pembimbing->id);
            })->get();

            return view('pembimbing.produk.index', compact('produk'));
        }

        if ($user->role === 'admin') {
            $produk = ProdukMagang::with('pelajar')->get();
            return view('admin.produk.index', compact('produk'));
        }

        abort(403, 'Unauthorized access.');
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'pelajar') {
            abort(403, 'Akses hanya untuk pelajar.');
        }

        return view('pelajar.produk.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pelajar') {
            abort(403, 'Akses hanya untuk pelajar.');
        }

        $request->validate([
            'nama_produk' => 'required',
            // Tambahkan ppt/pptx agar konsisten dengan form 'accept' di view
            'file_produk' => 'required|mimes:pdf,doc,docx,ppt,pptx,zip,rar,mp4|max:20480',
        ]);

        $path = $request->file('file_produk')->store('produk_magang', 'public');

        ProdukMagang::create([
            'pelajar_id' => $user->pelajar->id,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'file_produk' => $path,
        ]);

        // === Kirim Notifikasi ke Pembimbing ===
        $pembimbingUser = $user->pelajar->pembimbing->user;

        $pembimbingUser->notify(
            new \App\Notifications\NotifikasiBaru(
                "Peserta {$user->pelajar->nama} telah mengunggah produk magang.",
                route('pembimbing.produk.index')
            )
        );

        return redirect()->route('pelajar.produk.index')->with('success', 'Produk berhasil dikirim!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $produk = ProdukMagang::findOrFail($id);

        // Hanya peserta pemilik file atau admin yang boleh edit
        if ($user->role === 'pelajar' && $produk->pelajar_id !== $user->pelajar->id) {
            abort(403, 'Tidak diizinkan!');
        }

        if ($user->role === 'pelajar') {
            return view('pelajar.produk.edit', compact('produk'));
        }

        if ($user->role === 'admin') {
            return view('admin.produk.edit', compact('produk'));
        }

        abort(403, 'Unauthorized');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            // Tambahkan ppt/pptx agar konsisten dengan form 'accept' di view
            'file_produk' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,zip,rar,mp4|max:20480',
        ]);

        $produk = ProdukMagang::findOrFail($id);

        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;

        if ($request->hasFile('file_produk')) {
            // Hapus file lama
            if ($produk->file_produk && file_exists(storage_path('app/public/' . $produk->file_produk))) {
                unlink(storage_path('app/public/' . $produk->file_produk));
            }

            $path = $request->file('file_produk')->store('produk_magang', 'public');
            $produk->file_produk = $path;
        }

        $produk->save();

        return back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $produk = ProdukMagang::findOrFail($id);

        // Hanya peserta pemilik file atau admin
        if ($user->role === 'pelajar' && $produk->pelajar_id !== $user->pelajar->id) {
            abort(403, 'Tidak diizinkan!');
        }

        // Hapus file fisik
        if ($produk->file_produk && file_exists(storage_path('app/public/' . $produk->file_produk))) {
            unlink(storage_path('app/public/' . $produk->file_produk));
        }

        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}
