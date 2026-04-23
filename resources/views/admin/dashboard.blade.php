@extends('layouts.admin')

@section('content')
@php
    use Carbon\Carbon;
    $today = Carbon::today();

    $totalBuku = \App\Models\Buku::count();
    $totalKategori = \App\Models\Kategori::count();
    $totalTransaksi = \App\Models\Transaksi::count();

    $dipinjam = \App\Models\Transaksi::whereNull('tanggal_kembali')->count();
    $kembali = \App\Models\Transaksi::whereNotNull('tanggal_kembali')->count();

    $terlambat = \App\Models\Transaksi::whereNull('tanggal_kembali')
                    ->whereDate('jatuh_tempo', '<', $today)
                    ->count();
@endphp

<style>
    .custom-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .custom-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-shape {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fb6340 0%, #fbb140 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #f5365c 0%, #f56036 100%); }
</style>

<div class="container-fluid px-4 py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Dashboard Admin</h2>
            <p class="text-muted small mb-0">Selamat datang kembali, admin! Berikut ringkasan hari ini.</p>
        </div>
        <div class="text-muted small">
            <i class="fas fa-calendar-alt me-1"></i> {{ $today->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>

    {{-- Main Stats --}}
    <div class="row g-4">
        {{-- TOTAL BUKU --}}
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card shadow-sm border-0 h-100 text-white bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase small fw-bold opacity-75">Total Koleksi</div>
                            <h2 class="fw-bold mb-0 mt-1">{{ $totalBuku }}</h2>
                        </div>
                        <div class="icon-shape">
                            <i class="fas fa-book fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small">
                        <span class="opacity-75">Buku di Perpustakaan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KATEGORI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card shadow-sm border-0 h-100 text-white bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase small fw-bold opacity-75">Kategori</div>
                            <h2 class="fw-bold mb-0 mt-1">{{ $totalKategori }}</h2>
                        </div>
                        <div class="icon-shape">
                            <i class="fas fa-tags fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small">
                        <span class="opacity-75">Klasifikasi Buku</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TRANSAKSI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card shadow-sm border-0 h-100 text-white bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase small fw-bold opacity-75">Total Transaksi</div>
                            <h2 class="fw-bold mb-0 mt-1">{{ $totalTransaksi }}</h2>
                        </div>
                        <div class="icon-shape">
                            <i class="fas fa-exchange-alt fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small">
                        <span class="opacity-75">Riwayat Peminjaman</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TERLAMBAT --}}
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card shadow-sm border-0 h-100 text-white bg-gradient-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase small fw-bold opacity-75">Terlambat</div>
                            <h2 class="fw-bold mb-0 mt-1">{{ $terlambat }}</h2>
                        </div>
                        <div class="icon-shape">
                            <i class="fas fa-clock fs-3"></i>
                        </div>
                    </div>
                    <div class="mt-3 small">
                        <span class="opacity-75 font-weight-bold"><i class="fas fa-exclamation-circle"></i> Segera Tindak Lanjuti</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Row --}}
    <div class="row g-4 mt-2">
        {{-- Status Pinjam --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0 text-dark">Status Peminjaman Aktif</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <h4 class="fw-bold text-primary mb-1">{{ $dipinjam }}</h4>
                            <p class="text-muted small mb-0">Sedang Dipinjam</p>
                        </div>
                        <div class="col-6">
                            <h4 class="fw-bold text-success mb-1">{{ $kembali }}</h4>
                            <p class="text-muted small mb-0">Sudah Kembali</p>
                        </div>
                    </div>
                    {{-- Progress Bar Simple --}}
                    <div class="progress mt-4" style="height: 10px; border-radius: 5px;">
                        @php
                            $total = $dipinjam + $kembali ?: 1;
                            $percentDipinjam = ($dipinjam / $total) * 100;
                        @endphp
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentDipinjam }}%"></div>
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ 100 - $percentDipinjam }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Welcome --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 bg-light" style="border-radius: 15px;">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-center">
                        <span class="display-4">👋</span>
                        <h5 class="fw-bold mt-3">Halo, Admin!</h5>
                        <p class="text-muted small">Kelola data buku dan pantau transaksi harian dengan mudah di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection