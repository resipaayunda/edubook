@extends('layouts.user')

@section('content')
<style>
    /* Styling Header & Judul */
    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
    }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid #edf2f7;
    }

    /* Table Styling */
    .custom-table-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        background: #fff;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        padding: 1.2rem 1rem;
        border: none;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f1f5f9 !important;
    }

    .table td {
        padding: 1rem;
        color: #475569;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    /* Badge & Button Custom */
    .badge-stok {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .btn-pinjam {
        background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-weight: 600;
        transition: transform 0.2s;
    }

    .btn-pinjam:hover {
        transform: scale(1.05);
        color: white;
    }

    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 20px;
    }
    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid #f1f5f9;
    }
    .form-label-custom {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Daftar Buku & Inventaris</h1>
            <p class="text-muted small mb-0">Jelajahi koleksi buku terbaik kami dan ajukan peminjaman.</p>
        </div>
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
            <i class="fas fa-book-open fa-lg"></i>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="card filter-card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-filter text-muted"></i></span>
                            <select name="kategori_id" class="form-select border-0 bg-light rounded-end shadow-none">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-none" style="border-radius: 10px;">
                            Cari Buku
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL BUKU --}}
    <div class="card custom-table-card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-center">
                    <tr>
                        <th width="50">No</th>
                        <th class="text-start">Informasi Buku</th>
                        <th class="text-start">Penulis</th>
                        <th>Kategori</th>
                        <th>Status Stok</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @forelse ($bukus as $b) 
                <tr>
                    <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                    <td class="text-start">
                        <div class="fw-bold text-dark fs-6">{{ $b->judul_buku }}</div>
                        <span class="text-muted" style="font-size: 0.7rem;">ID: BUKU-00{{ $b->id }}</span>
                    </td>
                    <td class="text-start">
                        <div class="small fw-semibold"><i class="fas fa-user-edit me-1 opacity-50"></i>{{ $b->penulis }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-primary border border-primary border-opacity-10 px-3 py-2">
                            {{ $b->kategori->nama ?? 'Umum' }}
                        </span>
                    </td>
                    <td>
                        @if($b->stok > 0)
                            <span class="badge-stok bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle me-1"></i> Tersedia ({{ $b->stok }})
                            </span>
                        @else
                            <span class="badge-stok bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-times-circle me-1"></i> Stok Habis
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($b->stok > 0)
                        <button class="btn btn-primary btn-sm btn-pinjam fw-bold"
                                data-bs-toggle="modal"
                                data-bs-target="#pinjam{{ $b->id }}">
                            Pinjam Buku
                        </button>
                        @else
                        <button class="btn btn-secondary btn-sm opacity-50 fw-bold rounded-3" disabled>
                            Kosong
                        </button>
                        @endif
                    </td>
                </tr>

                {{-- MODAL PINJAM (DI-DESAIN ULANG) --}}
                {{-- MODAL PINJAM (DI-DESAIN ULANG) --}}
<div class="modal fade" id="pinjam{{ $b->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <form action="{{ route('user.buku.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="fw-bold text-dark mb-0">Konfirmasi Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <div class="bg-primary text-white rounded-3 p-3 me-3">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Anda akan meminjam buku:</div>
                            <div class="fw-bold text-dark">{{ $b->judul_buku }}</div>
                        </div>
                    </div>

                    <input type="hidden" name="buku_id" value="{{ $b->id }}">

                    <div class="row g-3">

                        {{-- 🔥 TAMBAHAN: JUMLAH PINJAM --}}
                        <div class="col-md-12">
                            <label class="form-label-custom">Jumlah Buku</label>
                            <input type="number"
                                   name="jumlah"
                                   min="1"
                                   max="{{ $b->stok }}"
                                   value="1"
                                   class="form-control border-0 bg-light p-2 rounded-3 shadow-none"
                                   required>
                            <div class="small text-muted mt-1">
                                Stok tersedia: {{ $b->stok }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Pinjam</label>
                            <input type="date"
                                   name="tanggal_pinjam"
                                   value="{{ date('Y-m-d') }}"
                                   class="form-control border-0 bg-light p-2 rounded-3 shadow-none"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Jatuh Tempo</label>
                            <input type="date"
                                   name="jatuh_tempo"
                                   class="form-control border-0 bg-light p-2 rounded-3 shadow-none"
                                   required>
                        </div>

                    </div>

                    <div class="mt-3 small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Pastikan mengembalikan buku sebelum jatuh tempo.
                    </div>

                </div>

                <div class="modal-footer p-3 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-primary fw-bold px-4 shadow-sm"
                            style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); border: none;">
                        Proses Pinjam
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

                @empty
                <tr>
                    <td colspan="6" class="py-5">
                        <div class="text-center">
                            <i class="fas fa-search-minus display-4 text-muted opacity-25 mb-3"></i>
                            <p class="text-muted">Buku yang anda cari tidak ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection