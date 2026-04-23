@extends('layouts.admin')

@section('content')
<style>
    .custom-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }
    /* Gradient Header Senada Dashboard */
    .bg-gradient-book { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
    }
    /* Input Style */
    .custom-input {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    .custom-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background-color: #fff;
    }
    /* Tabel Berwarna */
    .table-colored thead th {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        border: none;
    }
    /* Warna baris selang-seling */
    .table-colored tbody tr:nth-child(even) {
        background-color: #f8fafc; 
    }
    .table-colored tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
    .table-colored tbody tr:hover {
        background-color: #f0f4ff !important;
    }
    /* Badge Kategori Modern */
    .badge-kategori {
        background-color: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="row mb-4 text-center text-md-start">
        <div class="col">
            <h2 class="fw-bold text-dark mb-1">Manajemen Data Buku</h2>
            <p class="text-muted small">Kelola koleksi, penulis, dan ketersediaan stok buku perpustakaan.</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- FORM TAMBAH --}}
        <div class="col-lg-4">
            <div class="card custom-card shadow-sm">
                <div class="card-header bg-gradient-book p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                            <i class="fas fa-book-medical text-white fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-white mb-0">Tambah Buku</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.buku.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                            <input name="judul_buku" class="form-control custom-input" placeholder="Masukkan judul buku..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Penulis</label>
                            <input name="penulis" class="form-control custom-input" placeholder="Nama lengkap penulis" required>
                        </div>
                        <div class="row">
                            <div class="col-md-7 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Kategori</label>
                                <select name="kategori_id" class="form-select custom-input" required>
                                    <option value="" selected disabled>-- Pilih --</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Stok</label>
                                <input type="number" name="stok" class="form-control custom-input" placeholder="0" required>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px; background: #667eea; border: none;">
                            <i class="fas fa-save me-2"></i> Simpan Koleksi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABEL DATA --}}
        <div class="col-lg-8">
            <div class="card custom-card shadow-sm h-100">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="fw-bold mb-0 text-dark">Koleksi Tersedia</h5>
                    <form method="GET" action="{{ route('admin.buku.index') }}">
                        <select name="kategori" class="form-select form-select-sm custom-input shadow-none" style="min-width: 160px;" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-colored align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-3">Detail Buku</th>
                                    <th class="py-3">Kategori</th>
                                    <th class="py-3 text-center">Stok</th>
                                    <th class="py-3 text-center pe-4" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bukus as $b)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $b->judul_buku }}</div>
                                        <div class="text-muted small"><i class="fas fa-user-edit me-1" style="font-size: 10px;"></i>{{ $b->penulis }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-kategori">
                                            {{ $b->kategori->nama ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($b->stok < 5)
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                                {{ $b->stok }} <small>(Limit)</small>
                                            </span>
                                        @else
                                            <span class="fw-bold text-dark">{{ $b->stok }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn-action text-warning" data-bs-toggle="modal" data-bs-target="#edit{{ $b->id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <form action="{{ route('admin.buku.destroy', $b->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn-action text-danger" onclick="return confirm('Hapus buku ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div class="modal fade" id="edit{{ $b->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                                <h5 class="fw-bold"><i class="fas fa-edit text-warning me-2"></i>Edit Buku</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.buku.update', $b->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                                                        <input name="judul_buku" value="{{ $b->judul_buku }}" class="form-control custom-input" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted text-uppercase">Penulis</label>
                                                        <input name="penulis" value="{{ $b->penulis }}" class="form-control custom-input" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label small fw-bold text-muted text-uppercase">Kategori</label>
                                                            <select name="kategori_id" class="form-select custom-input" required>
                                                                @foreach($kategoris as $k)
                                                                    <option value="{{ $k->id }}" {{ $b->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label small fw-bold text-muted text-uppercase">Stok</label>
                                                            <input type="number" name="stok" value="{{ $b->stok }}" class="form-control custom-input" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 p-4 pt-0">
                                                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button class="btn btn-primary rounded-3 px-4 shadow-sm" style="background: #667eea; border:none;">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="fas fa-book-open display-4 mb-3"></i>
                                            <p>Belum ada koleksi buku.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection