@extends('layouts.user')

@section('content')
<style>
    /* Global Nuansa Biru */
    :root {
        --primary-blue: #0072ff;
        --light-blue: #e0f2fe;
        --dark-blue: #1e3a8a;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
    }

    /* Grid Layout */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
    }

    /* Card Styling */
    .book-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .book-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 114, 255, 0.1);
        border-color: var(--primary-blue);
    }

    /* Image/Icon Placeholder */
    .book-cover-wrapper {
        background-color: #f8fafc;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .book-icon-circle {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .book-icon-circle i {
        font-size: 3rem;
        color: #10b981; /* Hijau seperti di gambar contoh */
    }

    .check-badge {
        position: absolute;
        bottom: 40px;
        right: 70px;
        background: #10b981;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
    }

    /* Content Styling */
    .book-info {
        padding: 20px;
        text-align: center;
        flex-grow: 1;
    }

    .book-category-tag {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary-blue);
        background: var(--light-blue);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 10px;
        display: inline-block;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .book-author {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 15px;
    }

    /* Button Styling */
    .btn-pinjam-container {
        padding: 0 20px 20px 20px;
    }

    .btn-pinjam-custom {
        background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 10px;
        width: 100%;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-pinjam-custom:hover {
        box-shadow: 0 8px 15px rgba(0, 114, 255, 0.3);
        color: white;
        filter: brightness(1.1);
    }

    .btn-pinjam-custom:disabled {
        background: #e2e8f0;
        color: #94a3b8;
    }

    /* Modal Styling */
    .modal-content { border-radius: 25px; border: none; }
    .form-label-custom { font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
</style>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Koleksi Buku Digital</h1>
            <p class="text-muted small mb-0">Pilih buku favoritmu dan pinjam dengan sekali klik.</p>
        </div>
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
            <i class="fas fa-th-large fa-lg"></i>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            <select name="kategori_id" class="form-select border-0 bg-light shadow-none">
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
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- GRID BUKU --}}
    <div class="book-grid">
        @forelse ($bukus as $b)
        <div class="book-card shadow-sm">
            {{-- Bagian Atas: Ikon/Cover --}}
            <div class="book-cover-wrapper">
                <div class="book-icon-circle">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="check-badge">
                    <i class="fas fa-check fa-xs"></i>
                </div>
            </div>

            {{-- Bagian Tengah: Info --}}
            <div class="book-info">
                <span class="book-category-tag">
                    {{ $b->kategori->nama ?? 'Umum' }}
                </span>
                <h3 class="book-title">{{ $b->judul_buku }}</h3>
                <p class="book-author">{{ $b->penulis }}</p>
                
                @if($b->stok > 0)
                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 mb-2" style="font-size: 0.7rem;">
                        Tersedia: {{ $b->stok }}
                    </div>
                @else
                    <div class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 mb-2" style="font-size: 0.7rem;">
                        Stok Habis
                    </div>
                @endif
            </div>

            {{-- Bagian Bawah: Tombol --}}
            <div class="btn-pinjam-container">
                @if($b->stok > 0)
                <button class="btn btn-pinjam-custom fw-bold"
                        data-bs-toggle="modal"
                        data-bs-target="#pinjam{{ $b->id }}">
                    Pinjam Buku
                </button>
                @else
                <button class="btn btn-pinjam-custom fw-bold" disabled>
                    Kosong
                </button>
                @endif
            </div>
        </div>

        {{-- MODAL PINJAM --}}
        <div class="modal fade" id="pinjam{{ $b->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <form action="{{ route('user.buku.store') }}" method="POST">
                        @csrf
                        <div class="modal-header border-0">
                            <h5 class="fw-bold text-dark mb-0">Konfirmasi Pinjam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                                <div class="bg-primary text-white rounded-3 p-3 me-3">
                                    <i class="fas fa-bookmark fa-lg"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Judul Buku:</div>
                                    <div class="fw-bold text-dark">{{ $b->judul_buku }}</div>
                                </div>
                            </div>
                            <input type="hidden" name="buku_id" value="{{ $b->id }}">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label-custom">Jumlah</label>
                                    <input type="number" name="jumlah" min="1" max="{{ $b->stok }}" value="1" class="form-control border-0 bg-light p-2 rounded-3 shadow-none" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" class="form-control border-0 bg-light p-2 rounded-3 shadow-none" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">Jatuh Tempo</label>
                                    <input type="date" name="jatuh_tempo" class="form-control border-0 bg-light p-2 rounded-3 shadow-none" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-3">
                            <button type="button" class="btn btn-light fw-bold px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); border: none;">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <div class="col-12 py-5 text-center">
            <i class="fas fa-search-minus display-4 text-muted opacity-25 mb-3"></i>
            <p class="text-muted">Buku tidak ditemukan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection