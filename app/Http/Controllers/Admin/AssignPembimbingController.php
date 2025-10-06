<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use App\Models\Pembimbing;
use Illuminate\Http\Request;

class AssignPembimbingController extends Controller
{
    public function index()
    {
        $pelajars = Pelajar::where('status', 'disetujui')->with('pembimbing')->get();
        $pembimbings = Pembimbing::with('user')->get();
        return view('admin.pelajar.assignpembimbing', compact('pelajars', 'pembimbings'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'pembimbing_id' => 'required|exists:pembimbings,id',
        ]);

        $pelajar = Pelajar::findOrFail($id);
        $pelajar->pembimbing_id = $request->pembimbing_id;
        $pelajar->save();

        return back()->with('success', 'Pembimbing berhasil ditetapkan!');
    }
}
