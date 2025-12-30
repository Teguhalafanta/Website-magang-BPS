<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelajar;
use Illuminate\Http\Request;

class PelajarController extends Controller
{
    // Menampilkan daftar peserta dan form assign pembimbing
    public function assignView()
    {
        $pelajars = Pelajar::with('pembimbing', 'user')->get();
        $pembimbings = User::where('role', 'pembimbing')->get();

        return view('admin.pelajar.assignpembimbing', compact('pelajars', 'pembimbings'));
    }

    // Menetapkan pembimbing ke peserta tertentu
    public function assignPembimbing(Request $request, $id)
    {
        $request->validate([
            'pembimbing_id' => 'required|exists:users,id',
        ]);

        $pelajar = Pelajar::findOrFail($id);
        $pelajar->pembimbing_id = $request->pembimbing_id;
        $pelajar->save();

        return redirect()->back()->with('success', 'Pembimbing berhasil ditetapkan untuk peserta.');
    }
}
