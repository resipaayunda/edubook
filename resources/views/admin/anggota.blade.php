@extends('layouts.admin')

@section('content')
<style>
    .custom-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-shape {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-gradient-member { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        color: #667eea;
    }
    .table-container {
        border-radius: 15px;
        overflow: hidden;
    }
</style>

<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Kelola Anggota</h2>
            <p class="text-muted small mb-0">Manajemen hak akses dan data pengguna perpustakaan.</p>
        </div>
        <button class="btn btn-primary shadow-sm px-4 mt-3 mt-md-0" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
        </button>
    </div>

    {{-- Statistik Card (Konsep Sama dengan Dashboard) --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card custom-card shadow-sm border-0 text-white bg-gradient-member">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold opacity-75">Total Anggota</div>
                            <h2 class="fw-bold mb-0 mt-1">{{ $anggotas->count() }}</h2>
                        </div>
                        <div class="icon-shape">
                            <i class="fas fa-users fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel User --}}
    <div class="card border-0 shadow-sm table-container">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="m-0 fw-bold text-dark">Daftar Pengguna</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-muted small uppercase">PENGGUNA</th>
                            <th class="py-3 border-0 text-muted small uppercase">ROLE</th>
                            <th class="py-3 border-0 text-muted small uppercase">STATUS</th>
                            <th class="py-3 border-0 text-center text-muted small uppercase">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggotas as $anggota)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($anggota->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $anggota->name }}</div>
                                        <div class="text-muted small">{{ $anggota->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $anggota->role == 'Admin' ? 'bg-soft-danger text-danger' : 'bg-soft-primary text-primary' }} px-3 py-2" 
                                      style="background-color: {{ $anggota->role == 'Admin' ? '#ffe5e5' : '#e5edff' }}; border-radius: 8px;">
                                    {{ $anggota->role }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                    <small class="text-success fw-bold">Aktif</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <button class="btn btn-white btn-sm px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditAnggota{{ $anggota->id }}"
                                            title="Edit">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                                    <form action="{{ route('admin.anggota.destroy', $anggota->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm px-3" title="Hapus">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL TAMBAH USER ================= --}}
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold mt-2">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.anggota.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    @if ($errors->any())
                        <div class="alert alert-danger small py-2">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Nama Lengkap</label>
                        <input name="name" class="form-control border-0 bg-light" placeholder="Masukkan nama" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Email</label>
                        <input name="email" type="email" class="form-control border-0 bg-light" placeholder="email@contoh.com" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Password</label>
                        <input name="password" type="password" class="form-control border-0 bg-light" placeholder="Minimal 6 karakter" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Role Akses</label>
                        <select name="role" class="form-select border-0 bg-light" required style="border-radius: 10px;">
                            <option value="">Pilih Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Petugas">User</option>
                           
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT USER ================= --}}
@foreach ($anggotas as $anggota)
<div class="modal fade" id="modalEditAnggota{{ $anggota->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold mt-2">Edit Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Nama Lengkap</label>
                        <input name="name" class="form-control border-0 bg-light" value="{{ $anggota->name }}" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Email</label>
                        <input name="email" type="email" class="form-control border-0 bg-light" value="{{ $anggota->email }}" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Role Akses</label>
                        <select name="role" class="form-select border-0 bg-light" required style="border-radius: 10px;">
                            <option value="Admin" {{ $anggota->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Petugas" {{ $anggota->role == 'User' ? 'selected' : '' }}>User</option>
                           
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection