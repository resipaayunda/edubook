<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // PERBAIKAN: Hanya pakai relasi 'user'
        $query = Transaksi::with(['user'])->latest();

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
        return view('admin.laporan', compact('laporans'));
    }

    public function exportPdf(Request $request)
    {
        // PERBAIKAN: Hapus 'buku' di sini juga supaya download PDF tidak error
        $query = Transaksi::with(['user']);

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
        
        $pdf = Pdf::loadView('admin.laporan_pdf', compact('laporans'));
        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
    }
}