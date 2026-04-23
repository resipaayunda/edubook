<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman; // ✅ INI YANG KURANG
use App\Models\Buku;       // (kalau dipakai)
use Illuminate\Support\Facades\Auth;

class UserPengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with('user')
        ->where('user_id', Auth::id())
        ->whereNull('tanggal_kembali')
        ->get();

        return view('user.pengembalian', compact('peminjamans'));
    }

    public function kembalikan($id)
{
    $peminjaman = \App\Models\Peminjaman::findOrFail($id);

    // Cegah double klik (biar ga dobel balik)
    if ($peminjaman->tanggal_kembali) {
        return back()->with('error', 'Buku sudah dikembalikan');
    }

    // Update pengembalian
    $peminjaman->update([
        'tanggal_kembali' => now(),
        'status' => 'Kembali'
    ]);

    return back()->with('success', 'Buku berhasil dikembalikan');
}
}