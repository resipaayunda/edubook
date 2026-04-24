<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data transaksi hanya milik user yang sedang login
        $query = Transaksi::where('user_id', auth()->id())->latest();

        // Logika Filter Tanggal
        if ($request->filter && $request->tanggal) {
            if ($request->filter == 'hari') {
                $query->whereDate('tanggal_pinjam', $request->tanggal);
            } 
            elseif ($request->filter == 'minggu' || $request->filter == 'bulan') {
                $start = Carbon::parse($request->tanggal)->startOfDay();
                $end = $request->tanggal_sampai 
                        ? Carbon::parse($request->tanggal_sampai)->endOfDay() 
                        : $start;
                $query->whereBetween('tanggal_pinjam', [$start, $end]);
            }
        }

        $laporans = $query->get();
        
        // PERBAIKAN DI SINI: Sesuaikan dengan folder 'user' kamu
        return view('user.laporan', compact('laporans'));
    }

    public function exportPdf(Request $request)
    {
        $query = Transaksi::where('user_id', auth()->id());

        if ($request->filter && $request->tanggal) {
            if ($request->filter == 'hari') {
                $query->whereDate('tanggal_pinjam', $request->tanggal);
            } else {
                $start = Carbon::parse($request->tanggal)->startOfDay();
                $end = $request->tanggal_sampai 
                        ? Carbon::parse($request->tanggal_sampai)->endOfDay() 
                        : $start;
                $query->whereBetween('tanggal_pinjam', [$start, $end]);
            }
        }

        $laporans = $query->get();
        
        // PERBAIKAN DI SINI: Sesuaikan dengan folder 'user' kamu
        $pdf = Pdf::loadView('user.laporan_pdf', compact('laporans'));
        return $pdf->download('laporan-saya-' . now()->format('Y-m-d') . '.pdf');
    }
}