<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Transaksi::whereNotNull('tanggal_kembali')
            ->latest()
            ->get();

        return view('admin.pengembalian', compact('pengembalians'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
        ]);

        $data = Transaksi::findOrFail($id);

        $data->update([
            'tanggal_kembali' => $request->tanggal_kembali,
        ]);

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Data pengembalian berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = Transaksi::findOrFail($id);
        $data->delete();

        return redirect()->back()
            ->with('success', 'Data pengembalian dihapus');
    }
}