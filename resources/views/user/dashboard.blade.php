@extends('layouts.user')

@section('content')
@php
    use Carbon\Carbon;
    $today = Carbon::today();
@endphp

<style>
    /* Stats Card Gradient Styles */
    .stat-card {
        border-radius: 16px;
        border: none;
        color: white;
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card-body {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 120px;
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        opacity: 0.85;
        letter-spacing: 0.05em;
    }
    
    /* Icon Box Putih Transparan */
    .icon-box-white {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
    }

    /* Gradient Colors */
    .bg-gradient-blue   { background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); }
    .bg-gradient-green  { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-red    { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }

    /* Welcome Card */
    .welcome-card {
        border-radius: 15px;
        border-left: 6px solid #0072ff !important;
        background: #fdfdfd;
    }

    /* Table Styling */
    .custom-table-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 1rem;
        border-bottom: 2px solid #edf2f7;
    }
    .badge-soft {
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Dashboard User</h3>
        <p class="text-muted small">Selamat datang kembali di sistem perpustakaan digital.</p>
    </div>

    {{-- WELCOME MESSAGE --}}
    <div class="card welcome-card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="me-3 fs-1">👋</div>
                <div>
                    <h5 class="fw-bold mb-1 text-dark">Halo, {{ Auth::user()->name }}!</h5>
                    <p class="mb-0 text-muted small">
                        Senang melihat Anda kembali. Cek riwayat peminjaman dan pastikan buku Anda dikembalikan tepat waktu ya!
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIK CARDS (Gaya Admin) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card bg-gradient-blue shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label">Sedang Dipinjam</div>
                        <div class="stat-number">{{ $transaksis->whereNull('tanggal_kembali')->count() }}</div>
                        <div class="small opacity-75">Buku di tangan Anda</div>
                    </div>
                    <div class="icon-box-white">
                        <i class="fas fa-book-reader"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-gradient-green shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label">Sudah Kembali</div>
                        <div class="stat-number">{{ $transaksis->whereNotNull('tanggal_kembali')->count() }}</div>
                        <div class="small opacity-75">Selesai dipinjam</div>
                    </div>
                    <div class="icon-box-white">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-gradient-red shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label">Terlambat</div>
                        <div class="stat-number">
                            {{ $transaksis->filter(fn($t) => is_null($t->tanggal_kembali) && Carbon::parse($t->jatuh_tempo)->lt($today))->count() }}
                        </div>
                        <div class="small opacity-75">Segera kembalikan!</div>
                    </div>
                    <div class="icon-box-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT PEMINJAMAN --}}
    <div class="card custom-table-card shadow-sm border-0">
        <div class="card-header bg-white py-4 px-4 border-0 d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-pill p-2 me-3">
                <i class="fas fa-history small"></i>
            </div>
            <h6 class="mb-0 fw-bold text-dark">Riwayat Peminjaman Buku</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th class="ps-4">No</th>
                            <th class="text-start">Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($transaksis as $t)
                        @php
                            $jatuhTempo = Carbon::parse($t->jatuh_tempo);
                            $terlambat = is_null($t->tanggal_kembali) && $jatuhTempo->lt($today);
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#{{ $loop->iteration }}</td>
                            <td class="text-start fw-bold text-dark">{{ $t->buku }}</td>
                            <td class="small">{{ Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="small fw-bold {{ $terlambat ? 'text-danger' : '' }}">
                                {{ $jatuhTempo->format('d M Y') }}
                            </td>
                            <td class="small">
                                {{ $t->tanggal_kembali ? Carbon::parse($t->tanggal_kembali)->format('d M Y') : '--' }}
                            </td>
                            <td class="pe-4">
                                @if ($t->tanggal_kembali)
                                    <span class="badge-soft bg-success bg-opacity-10 text-success">Selesai</span>
                                @elseif ($terlambat)
                                    <span class="badge-soft bg-danger bg-opacity-10 text-danger">Terlambat</span>
                                @else
                                    <span class="badge-soft bg-primary bg-opacity-10 text-primary">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-muted small">
                                <i class="fas fa-book-open d-block mb-2 opacity-25 fs-1"></i>
                                Belum ada riwayat peminjaman buku.
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