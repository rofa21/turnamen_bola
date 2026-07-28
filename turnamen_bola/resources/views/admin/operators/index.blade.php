<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Operator SSB - Panitia Pusat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR SUPER ADMIN -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="text-center py-3 border-bottom border-secondary mb-3">
                <img src="/images/logo-turnamen.jpg" alt="Logo Panitia" class="rounded-circle border border-warning border-2 mb-2" style="width:48px;height:48px;object-fit:cover;">
                <h6 class="text-white fw-bold mt-2 mb-0">PANITIA PUSAT</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Disdikpora Grassroot Kebumen</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator ({{ $totalOperators }})</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.teams.index') }}"><i class="bi bi-shield-shaded me-2"></i> Data Tim & Verifikasi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedule.index') }}"><i class="bi bi-calendar-check me-2"></i> Jadwal Pertandingan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.print.index') }}"><i class="bi bi-printer-fill me-2"></i> Pusat Cetak Dokumen</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.export.index') }}"><i class="bi bi-file-earmark-excel-fill me-2"></i> Ekspor Data</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear-fill me-2"></i> Pengaturan Event</a></li>
                <hr class="border-secondary my-3">
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar Sistem
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i> Manajemen Akun Operator SSB</h2>
                    <p class="text-muted mb-0">Kelola kredensial akses login untuk Sekolah Sepak Bola peserta turnamen.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahOperator">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Akun Operator
                    </button>
                </div>
            </div>

            <!-- KARTU STATISTIK -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 bg-white border-start border-primary border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Akun Terdaftar</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalOperators }} <span class="fs-6 fw-normal text-muted">SSB</span></h4>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded text-primary fs-4">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 bg-white border-start border-success border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Akun Pernah Login</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $activeCount }} <span class="fs-6 fw-normal text-muted">SSB</span></h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded text-success fs-4">
                                <i class="bi bi-activity"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 bg-white border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Belum Pernah Login</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $inactiveCount }} <span class="fs-6 fw-normal text-muted">SSB</span></h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded text-warning fs-4">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL DAFTAR OPERATOR -->
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-nested text-secondary me-2"></i> Daftar Akun Operator SSB</h5>
                    <form action="{{ route('admin.operators.index') }}" method="GET" class="input-group" style="width: 300px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari Nama SSB / Operator...">
                        <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Sekolah Sepak Bola (SSB)</th>
                                <th>Nama Operator / Kontak</th>
                                <th>Username Login</th>
                                <th>Status Akun</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operators as $index => $op)
                                <tr>
                                    <td>{{ $operators->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $op->name }}</span><br>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>Kec. {{ $op->district ?? '-' }}</small>
                                    </td>
                                    <td>{{ $op->pic_name }} <br><small class="text-muted">{{ $op->phone ?? '-' }}</small></td>
                                    <td><code>{{ $op->username }}</code></td>
                                    <td>
                                        @if($op->last_login_at)
                                            <span class="badge bg-success">Aktif (Login: {{ $op->last_login_at->diffForHumans() }})</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Login</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" title="Edit Akun" data-bs-toggle="modal" data-bs-target="#modalEditOperator{{ $op->id }}"><i class="bi bi-pencil-square"></i></button>
                                        <form action="{{ route('admin.operators.destroy', $op->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Akun"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada akun operator terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $operators->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

<!-- MODALS OUTSIDE TABLE -->
@foreach($operators as $op)
    <div class="modal fade" id="modalEditOperator{{ $op->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.operators.update', $op->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Akun Operator</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Resmi SSB</label>
                            <input type="text" name="name" class="form-control" value="{{ $op->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Operator / PIC</label>
                            <input type="text" name="pic_name" class="form-control" value="{{ $op->pic_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nomor WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="{{ $op->phone }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Asal Kecamatan</label>
                            <input type="text" name="district" class="form-control" value="{{ $op->district }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Username Login (Readonly)</label>
                            <input type="text" class="form-control" value="{{ $op->username }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status Akun</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $op->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $op->status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- MODAL TAMBAH OPERATOR -->
<div class="modal fade" id="modalTambahOperator" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.operators.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus text-primary me-2"></i> Tambah Akun Operator SSB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Resmi Sekolah Sepak Bola (SSB)</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: SSB Garuda Muda Sruweng" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Penanggung Jawab / Operator</label>
                        <input type="text" name="pic_name" class="form-control" placeholder="Nama lengkap operator" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nomor WhatsApp (Aktif)</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kecamatan Asal</label>
                        <input type="text" name="district" class="form-control" placeholder="Contoh: Sruweng">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Username Login</label>
                        <input type="text" name="username" class="form-control" placeholder="contoh: op_garudamuda" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kata Sandi (Password)</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Akun Operator</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

