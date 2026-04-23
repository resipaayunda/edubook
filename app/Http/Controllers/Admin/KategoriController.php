<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::latest()->get();
        return view('admin.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kategori::create([
            'nama' => $request->nama
        ]);

        return redirect()->back()
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        Kategori::findOrFail($id)->update([
            'nama' => $request->nama
        ]);

        return redirect()->back()
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Kategori berhasil dihapus');
    }
}