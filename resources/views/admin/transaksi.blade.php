@extends('layouts.admin')

@section('content')
@php
    use Carbon\Carbon;
    $today = Carbon::today();
@endphp

<style>
    /* Custom Card Style */
    .custom-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    
    /* Stats Card Design */
    .stat-card {
        border-radius: 15px;
        border: none;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Table Styling */
    .table-colored thead th {
        background-color: #f1f5f9;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        border: none;
    }
    .table-colored tbody tr:nth-child(even) {
        background-color: #f8fafc; 
    }
    .table-colored tbody tr:hover {
        background-color: #f0f7ff !important;
    }

    /* Status Badges */
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
    }
    .btn-action:hover {
        background-color: #f8fafc;
        transform: scale(1.1);
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Log Transaksi</h2>
            <p class="text-muted small mb-0">Pantau aktivitas peminjaman dan pengembalian buku secara real-time.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm fw-bold" 
                style="border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;"
                data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus-circle me-2"></i>Tambah Transaksi
        </button>
    </div>

    {{-- STATISTIK (Konsep Dashboard) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold">Sedang Dipinjam</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $transaksis->whereNull('tanggal_kembali')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold">Sudah Kembali</div>
                        <div class="h3 mb-0 fw-bold text-dark">{{ $transaksis->whereNotNull('tanggal_kembali')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-bold">Terlambat</div>
                        <div class="h3 mb-0 fw-bold text-danger">
                            {{ $transaksis->filter(fn($t) => is_null($t->tanggal_kembali) && Carbon::parse($t->jatuh_tempo)->lt($today))->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card custom-card shadow-sm">
        <div class="card-header bg-white py-4 px-4 border-0">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list me-2 text-primary"></i>Riwayat Peminjaman</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-colored align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th class="ps-4">No</th>
                            <th class="text-start">Peminjam</th>
                            <th class="text-start">Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    @forelse ($transaksis as $t)
                    @php
                        $jatuhTempo = Carbon::parse($t->jatuh_tempo);
                        $terlambat = is_null($t->tanggal_kembali) && $jatuhTempo->lt($today);
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $t->user->name ?? 'User tidak ditemukan' }}</div>
                        </td>
                        <td class="text-start">
                            <span class="text-muted small fw-bold">{{ $t->buku }}</span>
                        </td>
                        <td class="small">{{ Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="small fw-bold">{{ $jatuhTempo->format('d M Y') }}</td>
                        <td class="small">
                            {!! $t->tanggal_kembali 
                                ? Carbon::parse($t->tanggal_kembali)->format('d M Y') 
                                : '<span class="text-muted">---</span>' !!}
                        </td>
                        <td>
                            @if ($t->tanggal_kembali)
                                <span class="badge-status bg-success bg-opacity-10 text-success">Kembali</span>
                            @elseif ($terlambat)
                                <span class="badge-status bg-danger bg-opacity-10 text-danger">Terlambat</span>
                            @else
                                <span class="badge-status bg-primary bg-opacity-10 text-primary">Dipinjam</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                @if (is_null($t->tanggal_kembali))
                                <form action="{{ route('admin.transaksi.update', $t->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button class="btn-action text-success" title="Konfirmasi Kembali" onclick="return confirm('Konfirmasi pengembalian?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                <button class="btn-action text-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $t->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.transaksi.destroy', $t->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn-action text-danger" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-muted py-5">
                            <i class="fas fa-folder-open display-4 mb-3 d-block opacity-25"></i>
                            Belum ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold">Input Transaksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.transaksi.store') }}" method="POST">
                @csrf

                <div class="modal-body p-4">

                    {{-- 🔥 FIX: DROPDOWN BUKU --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PILIH BUKU</label>
                        <select name="buku_id" class="form-control" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($bukus as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->judul_buku }} ({{ $b->kategori->nama ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">TGL PINJAM</label>
                            <input type="date" name="tanggal_pinjam" class="form-control" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">JATUH TEMPO</label>
                            <input type="date" name="jatuh_tempo" class="form-control" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Data</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach ($transaksis as $t)
<div class="modal fade" id="editModal{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold text-warning">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.transaksi.update', $t->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="modal-body p-4">

                    {{-- 🔥 FIX: DROPDOWN BUKU --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PILIH BUKU</label>
                        <select name="buku_id" class="form-control" required>
                            @foreach($bukus as $b)
                                <option value="{{ $b->id }}"
                                    {{ $t->buku_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->judul_buku }} ({{ $b->kategori->nama ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">TGL PINJAM</label>
                            <input type="date" name="tanggal_pinjam"
                                   value="{{ $t->tanggal_pinjam }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">JATUH TEMPO</label>
                            <input type="date" name="jatuh_tempo"
                                   value="{{ $t->jatuh_tempo }}"
                                   class="form-control" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 text-white shadow-sm">
                        Update Transaksi
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endforeach

@endsection