<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class BukuUserController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::all();

        $query = Buku::with('kategori');

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus = $query->get();

        return view('user.buku', compact('bukus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'jatuh_tempo' => 'required|date'
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok < 1) {
            return back()->with('error', 'Stok buku habis!');
        }

        // kurangi stok
        $buku->decrement('stok');

        // simpan transaksi
        Transaksi::create([
            'user_id' => Auth::id(),
            'buku' => $buku->judul_buku,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'jatuh_tempo' => $request->jatuh_tempo,
            'status' => 'dipinjam'
        ]);

        return back()->with('success', 'Buku berhasil dipinjam!');
    }
}