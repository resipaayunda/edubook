@extends('layouts.admin')

@section('content')
<style>
    .custom-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }
    /* Header Gradient - Tetap Senada Dashboard */
    .bg-gradient-category { 
        background: linear-gradient(135deg, #00BCD4 0%, #1171ef 100%); 
    }
    /* Input Style */
    .custom-input {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .custom-input:focus {
        border-color: #00BCD4;
        box-shadow: 0 0 0 4px rgba(0, 188, 212, 0.1);
        background-color: #fff;
    }
    /* Tabel Berwarna */
    .table-colored thead th {
        background-color: #f1f5f9; /* Warna header lebih solid */
        color: #334155;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        border: none;
    }
    /* Warna baris selang-seling yang lebih kelihatan */
    .table-colored tbody tr:nth-child(even) {
        background-color: #f8fafc; 
    }
    .table-colored tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
    /* Hover baris dengan warna biru sangat muda */
    .table-colored tbody tr:hover {
        background-color: #f0f9ff !important;
    }
    .text-category-name {
        color: #1e293b;
        font-weight: 600;
        font-size: 1rem;
    }
    .btn-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Manajemen Kategori</h2>
        <p class="text-muted">Kelola kategori buku dengan tampilan yang lebih rapi dan berwarna.</p>
    </div>

    <div class="row g-4">
        {{-- FORM TAMBAH (Ukuran col-lg-4, lebih pas tidak terlalu kecil) --}}
        <div class="col-lg-4">
            <div class="card custom-card shadow-sm h-100">
                <div class="card-header bg-gradient-category p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tag text-white fs-4 me-3"></i>
                        <h5 class="fw-bold text-white mb-0">Tambah Kategori</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.kategori.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nama Kategori Baru</label>
                            <input type="text" name="nama" class="form-control custom-input" placeholder="Masukkan nama kategori..." required>
                        </div>
                        <button type="submit" class="btn w-100 py-3 fw-bold text-white shadow-sm" style="border-radius: 12px; background: #00BCD4; border: none;">
                            <i class="fas fa-plus-circle me-2"></i> Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABEL DATA (col-lg-8) --}}
        <div class="col-lg-8">
            <div class="card custom-card shadow-sm h-100">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Kategori</h5>
                    <span class="badge py-2 px-3 text-white" style="background: #00BCD4; border-radius: 8px;">
                        Total: {{ $kategoris->count() }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-colored align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-3" width="100">NO</th>
                                    <th class="py-3">NAMA KATEGORI</th>
                                    <th class="py-3 text-center pe-4" width="180">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kategoris as $k)
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="text-category-name">{{ $k->nama }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-2 justify-content-center">
                                            {{-- EDIT --}}
                                            <button class="btn-action text-warning" data-bs-toggle="collapse" data-bs-target="#editKategori{{ $k->id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            
                                            {{-- HAPUS --}}
                                            <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn-action text-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- FORM EDIT INLINE --}}
                                <tr class="collapse" id="editKategori{{ $k->id }}">
                                    <td colspan="3" class="px-4 py-3" style="background-color: #f1f5f9;">
                                        <form action="{{ route('admin.kategori.update', $k->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf @method('PUT')
                                            <input type="text" name="nama" value="{{ $k->nama }}" class="form-control custom-input bg-white shadow-sm" required>
                                            <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;">Update</button>
                                            <button type="button" class="btn btn-secondary px-3" style="border-radius: 10px;" data-bs-toggle="collapse" data-bs-target="#editKategori{{ $k->id }}">Batal</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Data kosong</td>
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