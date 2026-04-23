<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Transaksi;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = \App\Models\Kategori::all();

        $bukus = \App\Models\Buku::with('kategori')
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->get();

        return view('admin.buku', compact('bukus', 'kategoris'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required',
            'penulis' => 'required',
            'stok' => 'required|integer|min:1',
            'kategori_id' => 'required'
        ]);

        Buku::create($request->all());

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_buku' => 'required',
            'penulis' => 'required',
            'stok' => 'required|integer|min:1',
            'kategori_id' => 'required'
        ]);

        Buku::findOrFail($id)->update([
            'judul_buku' => $request->judul_buku,
            'penulis' => $request->penulis,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id
        ]);

        return back()->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        Buku::findOrFail($id)->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }
}