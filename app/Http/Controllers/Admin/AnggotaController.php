<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::all();
        return view('admin.anggota', compact('anggotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:anggotas,email',
            'password' => 'required',
            'role' => 'required',
        ]);

        Anggota::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $anggota->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return back();
    }

    public function destroy($id)
    {
        Anggota::findOrFail($id)->delete();
        return back();
    }
}