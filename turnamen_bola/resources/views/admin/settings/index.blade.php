<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Event & Master Data - Panitia Pusat</title>
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
                <img src="{{ $activeEvent->logo_url ?? '/images/logo-turnamen.jpg' }}" alt="Logo Panitia" class="rounded-circle border border-warning border-2 mb-2" style="width:48px;height:48px;object-fit:cover;">
                <h6 class="text-white fw-bold mt-2 mb-0">PANITIA PUSAT</h6>
                <small class="text-muted text-uppercase" style="font-size: 0.68rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $activeEvent->name }}</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.teams.index') }}"><i class="bi bi-shield-shaded me-2"></i> Data Tim & Verifikasi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedule.index') }}"><i class="bi bi-calendar-check me-2"></i> Jadwal Pertandingan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.print.index') }}"><i class="bi bi-printer-fill me-2"></i> Pusat Cetak Dokumen</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.export.index') }}"><i class="bi bi-file-earmark-excel-fill me-2"></i> Ekspor Data</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear-fill me-2"></i> Pengaturan Event</a></li>
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Gagal menyimpan data:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-gear-fill text-primary me-2"></i> Pengaturan Event & Master Data</h2>
                    <p class="text-muted mb-0">Kelola identitas turnamen, logo resmi, kelompok usia, serta pencadangan database sistem.</p>
                </div>
            </div>

            <div class="row g-4">
                
                <!-- 1. IDENTITAS TURNAMEN & LOGO -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-shaded text-primary me-2"></i> Identitas & Logo Turnamen</h5>
                        <form action="{{ route('admin.settings.event') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Resmi Turnamen</label>
                                <input type="text" name="name" class="form-control" value="{{ $event->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Instansi / Penyelenggara</label>
                                <input type="text" name="organizer" class="form-control" value="{{ $event->organizer }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Lokasi Utama / Stadion</label>
                                <input type="text" name="location" class="form-control" value="{{ $event->location }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Musim / Tahun</label>
                                <input type="text" name="season" class="form-control" value="{{ $event->season }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Upload Logo Resmi (PNG/JPG)</label>
                                @if($event->logo_path)
                                    <div class="mb-2 d-flex align-items-center gap-3 p-2 bg-light border rounded">
                                        <img src="{{ asset('storage/'.$event->logo_path) }}" alt="Logo Saat Ini" style="height:56px;width:56px;object-fit:contain;border-radius:6px;background:#fff;border:1px solid #dee2e6;padding:4px;">
                                        <div>
                                            <small class="text-success fw-bold d-block"><i class="bi bi-check-circle-fill me-1"></i>Logo sudah tersimpan</small>
                                            <small class="text-muted">Upload baru untuk mengganti logo saat ini.</small>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div class="form-text">Format logo akan otomatis disesuaikan untuk kop surat dan ID card.</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Perubahan Identitas</button>
                        </form>
                    </div>
                </div>

                <!-- 2. MASTER KELOMPOK USIA -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-tags-fill text-success me-2"></i> Master Kelompok Usia (KU)</h5>
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalTambahKU"><i class="bi bi-plus-lg me-1"></i> Tambah Kategori</button>
                        </div>
                        <p class="text-muted small">Atur batas tahun kelahiran pemain agar sistem otomatis memverifikasi umur dari tgl lahir.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Batas Kelahiran (Maksimal)</th>
                                        <th>Status</th>
                                        <th class="text-center" width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $cat)
                                        <tr>
                                            <td class="fw-bold">{{ $cat->name }}</td>
                                            <td>Maksimal Tahun {{ $cat->max_birth_year }}</td>
                                            <td>
                                                @if($cat->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Non-Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalEditKU{{ $cat->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                                <form action="{{ route('admin.settings.category.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-2">Belum ada kategori. Klik "Tambah Kategori" untuk membuat baru.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-secondary small mb-0">
                            <i class="bi bi-info-circle-fill me-1 text-primary"></i> Perubahan batas tahun lahir akan memengaruhi verifikasi otomatis pada saat operator mendaftarkan pemain.
                        </div>
                    </div>
                </div>

                <!-- 3. BACKUP DATABASE -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-database-fill-gear text-success me-2"></i> Cadangan & Pemulihan Lengkap (Database + Foto/Dokumen)</h5>
                        <div class="alert alert-success border-start border-success border-4 mb-3 small">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Backup Lengkap:</strong> File backup berupa <code>.zip</code> yang memuat <code>database.sqlite</code> (semua data sistem) <strong>SEKALIGUS</strong> seluruh berkas foto & dokumen pemain yang telah diunggah. Cukup simpan satu file ZIP untuk backup menyeluruh.
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1"><i class="bi bi-file-zip-fill text-primary me-2"></i> Unduh Backup Lengkap (.zip)</h6>
                                        <small class="text-muted">Zip berisi: SQLite database + semua foto & dokumen pemain yang terunggah.</small>
                                    </div>
                                    <a href="{{ route('admin.settings.backup') }}" class="btn btn-primary btn-sm shadow-sm"><i class="bi bi-download me-1"></i> Download ZIP</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1"><i class="bi bi-file-earmark-arrow-up-fill text-danger me-2"></i> Pulihkan Dari Cadangan (.zip / .sqlite)</h6>
                                        <small class="text-muted">Upload file cadangan ZIP atau SQLite untuk memulihkan sistem secara menyeluruh.</small>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRestoreDatabase">
                                        <i class="bi bi-upload me-1"></i> Pulihkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<!-- MODAL IMPORT DATABASE -->
<div class="modal fade" id="modalRestoreDatabase" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.settings.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Peringatan: Import database akan memperbarui/menimpa data sistem saat ini dengan data dari file yang di-import. Lanjutkan?')">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i> Import Database System</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning small mb-3">
                        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Peringatan:</strong> Proses pemulihan akan <u>menimpa data sistem saat ini</u>. Pastikan file backup valid sebelum melanjutkan.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Pilih File Cadangan (.zip / .sqlite / .sql)</label>
                        <input type="file" name="backup_file" class="form-control" accept=".zip,.sql,.sqlite,.db,.txt" required>
                        <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Direkomendasikan: File <code>.zip</code> hasil backup lengkap (database + dokumen/foto).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-upload me-1"></i> Proses Import Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KELOMPOK USIA -->
<div class="modal fade" id="modalTambahKU" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.settings.category') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-warning me-2"></i> Tambah Kategori Kelompok Usia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Kategori (Contoh: KU-14)</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: KU-14" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Batas Maksimal Tahun Kelahiran</label>
                        <input type="number" name="max_birth_year" class="form-control" placeholder="Contoh: 2012" min="1990" max="2050" required>
                        <small class="text-muted">Pemain lahir pada atau setelah tahun ini akan dianggap sesuai kategori.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Status Kategori</label>
                        <select name="is_active" class="form-select">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALS EDIT KELOMPOK USIA -->
@foreach($categories as $cat)
    <div class="modal fade" id="modalEditKU{{ $cat->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.settings.category.update', $cat->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Kategori Kelompok Usia</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Batas Maksimal Tahun Kelahiran</label>
                            <input type="number" name="max_birth_year" class="form-control" value="{{ $cat->max_birth_year }}" min="1990" max="2050" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status Kategori</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $cat->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ ! $cat->is_active ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Perbarui Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

