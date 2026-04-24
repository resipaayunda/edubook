@extends('layouts.admin')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
    /* Card Style */
    .custom-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
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

    /* Badge Status Soft Style */
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
        text-decoration: none;
    }
    .btn-action:hover {
        transform: scale(1.1);
        background-color: #f8fafc;
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Riwayat Pengembalian</h2>
        <p class="text-muted small mb-0">Daftar buku yang telah dikembalikan oleh anggota.</p>
    </div>

    {{-- STATISTIK RINGKAS (Opsional - Agar senada dengan Dashboard) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex align-items-center">
                    <div class="ms-3">
                        <div class="small opacity-75 fw-bold">TOTAL PENGEMBALIAN</div>
                        <div class="h3 mb-0 fw-bold">{{ $pengembalians->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="d-flex align-items-center">
                    <div class="ms-3">
                        <div class="small opacity-75 fw-bold">PENGEMBALIAN BULAN INI</div>
                        <div class="h3 mb-0 fw-bold">{{ $pengembalians->where('tanggal_kembali', '>=', Carbon::now()->startOfMonth())->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE DATA --}}
    <div class="card custom-card shadow-sm">
        <div class="card-header bg-white py-4 px-4 border-0">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2 text-primary"></i>Data Log Pengembalian</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-colored align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th class="ps-4">No</th>
                            <th class="text-start">Nama Anggota</th>
                            <th class="text-start">Judul Buku</th>
                            <th>Pinjam</th>
                            <th>Tempo</th>
                            <th>Kembali</th>
                            <th>Status</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    @forelse ($pengembalians as $p)
                    @php
                        $jatuhTempo = Carbon::parse($p->jatuh_tempo);
                        $kembali = Carbon::parse($p->tanggal_kembali);
                        $telat = $kembali->gt($jatuhTempo);
                    @endphp

                    <tr>
                        <td class="ps-4 text-muted fw-bold">{{ $loop->iteration }}</td>
                        <td class="text-start fw-bold text-dark">{{ $p->user->name ?? '-' }}</td>
                        <td class="text-start small text-muted">{{ $p->buku }}</td>
                        <td class="small">{{ Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="small fw-bold">{{ $jatuhTempo->format('d M Y') }}</td>
                        <td class="small text-primary fw-bold">{{ $kembali->format('d M Y') }}</td>
                        <td>
                            @if ($telat)
                                <span class="badge-status bg-danger bg-opacity-10 text-danger text-uppercase" style="font-size: 0.65rem;">Terlambat</span>
                            @else
                                <span class="badge-status bg-success bg-opacity-10 text-success text-uppercase" style="font-size: 0.65rem;">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- EDIT --}}
                                <button class="btn-action text-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#edit{{ $p->id }}" 
                                        title="Edit Tanggal">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action text-danger" 
                                            onclick="return confirm('Hapus data pengembalian ini?')"
                                            title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="edit{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                <form action="{{ route('admin.pengembalian.update', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-0 pt-4 px-4">
                                        <h5 class="fw-bold"><i class="fas fa-edit me-2 text-warning"></i>Koreksi Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">TANGGAL PENGEMBALIAN</label>
                                            <input type="date" name="tanggal_kembali" value="{{ $p->tanggal_kembali }}" class="form-control border-0 bg-light" style="border-radius: 10px;" required>
                                        </div>
                                        <div class="alert alert-info border-0 small" style="border-radius: 10px;">
                                            <i class="fas fa-info-circle me-2"></i> Mengubah tanggal akan mempengaruhi status keterlambatan secara otomatis.
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-archive display-4 mb-3 d-block opacity-25"></i>
                            Belum ada riwayat pengembalian buku.
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