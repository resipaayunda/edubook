<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['user', 'buku'])->latest()->get();
        $bukus = Buku::with('kategori')->get();

        return view('admin.transaksi', compact('transaksis', 'bukus'));
    }

    public function store(Request $request)
{
    $request->validate([
        'buku_id' => 'required',
        'tanggal_pinjam' => 'required|date',
        'jatuh_tempo' => 'required|date',
    ]);

    $buku = \App\Models\Buku::findOrFail($request->buku_id);

    Transaksi::create([
        'user_id' => Auth::id(),
        'buku_id' => $request->buku_id,

        // 🔥 isi kolom lama supaya tidak error
        'buku' => $buku->judul_buku,

        'tanggal_pinjam' => $request->tanggal_pinjam,
        'jatuh_tempo' => $request->jatuh_tempo,
        'status' => 'dipinjam',
    ]);

    return redirect()->back()->with('success', 'Buku berhasil dipinjam!');
}

    public function update(Request $request, $id)
{
    $transaksi = Transaksi::findOrFail($id);

    $buku = \App\Models\Buku::findOrFail($request->buku_id);

    $transaksi->update([
        'buku_id' => $request->buku_id,
        'buku' => $buku->judul_buku, // 🔥 penting
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'jatuh_tempo' => $request->jatuh_tempo,
    ]);

    return back()->with('success', 'Data berhasil diupdate');
}

    public function destroy($id)
    {
        Transaksi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data dihapus!');
    }
}