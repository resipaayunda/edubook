@extends('layouts.user')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
    /* Stats Card Gradient Styles (Senada dengan Dashboard) */
    .stat-card {
        border-radius: 16px;
        border: none;
        color: white;
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-5px); }
    
    .stat-card-body {
        padding: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 100px;
    }
    .stat-number { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 0.05em; }

    /* Icon Box Putih Transparan */
    .icon-box-white {
        width: 45px; height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        background: rgba(255, 255, 255, 0.2);
    }

    /* Gradient Colors */
    .bg-gradient-blue   { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-gradient-orange { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }

    /* Table Styling */
    .custom-table-card { border-radius: 15px; border: none; overflow: hidden; background: #fff; }
    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 1rem;
        border: none;
    }
    .badge-soft {
        padding: 5px 10px; border-radius: 6px;
        font-weight: 700; font-size: 0.7rem; text-transform: uppercase;
    }
</style>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Pengembalian Buku</h3>
        <p class="text-muted small">Kembalikan buku yang sedang Anda pinjam tepat waktu.</p>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card bg-gradient-blue shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label">Sedang Dipinjam</div>
                        <div class="stat-number">{{ $peminjamans->count() }}</div>
                        <div class="small opacity-75">Buku yang harus dikembalikan</div>
                    </div>
                    <div class="icon-box-white">
                        <i class="fas fa-book-reader"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PENGEMBALIAN --}}
    <div class="card custom-table-card shadow-sm border-0">
        <div class="card-header bg-white py-4 px-4 border-0 d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-pill p-2 me-3">
                <i class="fas fa-exchange-alt small"></i>
            </div>
            <h6 class="mb-0 fw-bold text-dark">Daftar Pinjaman Aktif</h6>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th class="text-start">Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        @php
                            $jatuhTempo = Carbon::parse($p->jatuh_tempo);
                            $telat = now()->gt($jatuhTempo);
                        @endphp

                        <tr>
                            <td class="ps-4 text-muted fw-bold">{{ $loop->iteration }}</td>
                            <td class="text-start">
                                <div class="fw-bold text-dark">{{ $p->buku }}</div>
                                <div class="small text-muted">{{ $p->user->name ?? 'User' }}</div>
                            </td>
                            <td class="small">{{ Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="small fw-bold {{ $telat ? 'text-danger' : '' }}">
                                {{ $jatuhTempo->format('d M Y') }}
                            </td>
                            <td>
                                @if($telat)
                                    <span class="badge-soft bg-danger bg-opacity-10 text-danger">Terlambat</span>
                                @else
                                    <span class="badge-soft bg-primary bg-opacity-10 text-primary">Dipinjam</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <form action="{{ route('user.pengembalian.kembalikan', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm fw-bold px-3 shadow-sm" 
                                            style="border-radius: 8px;"
                                            onclick="return confirm('Apakah Anda yakin sudah mengembalikan buku ini?')">
                                        <i class="fas fa-undo-alt me-1"></i> Kembalikan
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-muted">
                                <i class="fas fa-check-circle display-4 mb-3 d-block opacity-25"></i>
                                Tidak ada buku yang sedang dipinjam.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection