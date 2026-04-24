@extends('layouts.user') {{-- Pastikan nama layout user kamu benar --}}

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
    .custom-card { border-radius: 15px; border: none; }
    .stat-card-colored { border-radius: 16px; border: none; color: white; transition: all 0.3s ease; position: relative; overflow: hidden; }
    .stat-card-colored:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .stat-card-body { padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; min-height: 120px; }
    .stat-number-small { font-size: 1.8rem; font-weight: 800; margin-bottom: 2px; line-height: 1.2; }
    .stat-label-small { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; opacity: 0.85; letter-spacing: 0.05em; }
    .icon-box-white { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; background: rgba(255, 255, 255, 0.2); flex-shrink: 0; }
    .bg-gradient-purple { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); }
    .bg-gradient-blue { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); }
    .bg-gradient-orange { background: linear-gradient(135deg, #f83600 0%, #f9d423 100%); }
    .filter-card { border-radius: 15px; border: 1px solid #edf2f7; background: #fff; }
    .table-laporan thead th { background-color: #f8fafc; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.7rem; padding: 1rem; border-bottom: 2px solid #edf2f7; }
    .badge-soft { padding: 5px 10px; border-radius: 6px; font-weight: 700; font-size: 0.7rem; text-transform: uppercase; }
</style>

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Riwayat Peminjaman Saya</h3>
            <p class="text-muted small mb-0">Daftar transaksi peminjaman barang Anda.</p>
        </div>
        <a href="{{ route('user.laporan.pdf', request()->all()) }}" class="btn btn-danger px-4 fw-bold shadow-sm" style="border-radius: 10px;">
            <i class="fas fa-file-pdf me-2"></i>Cetak PDF
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card-colored bg-gradient-purple shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label-small">Total Pinjam</div>
                        <div class="stat-number-small">{{ $laporans->count() }}</div>
                        <div class="small opacity-75">Transaksi Saya</div>
                    </div>
                    <div class="icon-box-white"><i class="fas fa-layer-group"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card-colored bg-gradient-blue shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label-small">Selesai</div>
                        <div class="stat-number-small">{{ $laporans->where('status', 'kembali')->count() }}</div>
                        <div class="small opacity-75">Sudah Dikembalikan</div>
                    </div>
                    <div class="icon-box-white"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card-colored bg-gradient-orange shadow-sm">
                <div class="stat-card-body">
                    <div>
                        <div class="stat-label-small">Masih Dipinjam</div>
                        <div class="stat-number-small">{{ $laporans->where('status', '!=', 'kembali')->count() }}</div>
                        <div class="small opacity-75">Belum Kembali</div>
                    </div>
                    <div class="icon-box-white"><i class="fas fa-history"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card filter-card shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('user.laporan.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">RENTANG WAKTU</label>
                        <select name="filter" class="form-select border-0 bg-light rounded-3">
                            <option value="">-- Semua Waktu --</option>
                            <option value="hari" {{ request('filter')=='hari' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="minggu" {{ request('filter')=='minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">DARI TANGGAL</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control border-0 bg-light rounded-3">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">SAMPAI TANGGAL</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-control border-0 bg-light rounded-3">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold rounded-3">Cari</button>
                        <a href="{{ route('user.laporan.index') }}" class="btn btn-outline-secondary rounded-3"><i class="fas fa-sync-alt"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-center">
                    <tr class="table-laporan">
                        <th class="ps-4">No</th>
                        <th>Tgl Pinjam</th>
                        <th class="text-start">Nama Barang</th>
                        <th>Tgl Kembali</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse($laporans as $i => $l)
                    <tr>
                        <td class="ps-4 text-muted fw-bold">{{ $i+1 }}</td>
                        <td class="small">{{ Carbon::parse($l->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td class="text-start text-muted small">{{ $l->buku }}</td>
                        <td class="small">{{ $l->tanggal_kembali ? Carbon::parse($l->tanggal_kembali)->format('d/m/Y') : '--' }}</td>
                        <td class="pe-4">
                            @if($l->status == 'kembali')
                                <span class="badge-soft bg-success bg-opacity-10 text-success">Selesai</span>
                            @else
                                <span class="badge-soft bg-warning bg-opacity-10 text-warning">Dipinjam</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-5 text-center text-muted small">Riwayat tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection