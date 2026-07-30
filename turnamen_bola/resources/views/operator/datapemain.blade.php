<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemain - {{ $operator->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --hijau: #1a5c2a; --hijau-tua: #0f3b1a; --emas: #d4a017; }
        body { background-color: #f0f4f0; font-family: 'Segoe UI', sans-serif; }
        .sidebar { background: linear-gradient(180deg, var(--hijau-tua) 0%, var(--hijau) 100%); }
        @media (min-width: 768px) { .sidebar { min-height: 100vh; } }
        .sidebar-brand { background: rgba(0,0,0,0.3); border-bottom: 2px solid var(--emas); }
        .sidebar .nav-link { color: #c8e6c9; margin-bottom: 3px; border-radius: 8px; transition: all 0.2s; padding: 8px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: linear-gradient(135deg, var(--emas) 0%, #b8870f 100%); }
        .doc-badge { font-size: .68rem; padding: 2px 6px; }
        .doc-section-title { background: #f8f9fa; border-left: 3px solid var(--hijau); padding: 6px 12px; border-radius: 4px; font-size: .82rem; font-weight: 600; color: var(--hijau); }
        .nav-pills .nav-link.active { background-color: var(--hijau); color: white; }
        .nav-pills .nav-link { color: var(--hijau-tua); background-color: #e8f5e9; margin-right: 6px; }
        .mobile-header { background: var(--hijau-tua); border-bottom: 2px solid var(--emas); }
    </style>
</head>
<body>

<!-- MOBILE TOP NAVBAR -->
<div class="mobile-header d-md-none p-2 text-white d-flex justify-content-between align-items-center sticky-top shadow-sm">
    <div class="d-flex align-items-center gap-2">
        <img src="{{ $team?->logo_url ?? '/images/logo-turnamen.jpg' }}" alt="Logo" class="rounded-circle border border-warning" style="width:36px;height:36px;object-fit:cover;">
        <div>
            <div class="fw-bold text-warning small">{{ $operator->name }}</div>
            <small class="text-light" style="font-size:.65rem;">Operator SSB</small>
        </div>
    </div>
    <button class="btn btn-outline-warning btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-5 me-1"></i> Menu
    </button>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
            <div class="sidebar-brand text-center p-3 d-none d-md-block">
                <img src="{{ $team?->logo_url ?? '/images/logo-turnamen.jpg' }}" alt="Logo Tim" class="rounded-circle border border-warning border-2 mb-2" style="width:48px;height:48px;object-fit:cover;">
                <div class="fw-bold text-warning" style="font-size:.85rem;">{{ $operator->name }}</div>
                <small class="text-light" style="font-size:.72rem;">Operator SSB</small>
            </div>
            <ul class="nav flex-column p-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.profile') }}"><i class="bi bi-building me-2"></i> Profil Tim SSB</a></li>
                <hr class="border-success my-2">
                <li class="nav-item">
                    <form action="{{ route('operator.logout') }}" method="POST">@csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <strong><i class="bi bi-exclamation-octagon-fill me-2"></i> Pengisian Belum Lengkap:</strong>
                    <ul class="mb-0 mt-1 small">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h3 fw-bold" style="color:var(--hijau-tua);"><i class="bi bi-people-fill text-success me-2"></i>Kelola Squad Pemain</h1>
                    <p class="text-muted mb-0 small">Input data pemain, unggah berkas dokumen, dan pantau verifikasi Admin Panitia.</p>
                </div>
                <div class="btn-toolbar mt-2 mt-md-0">
                    <button class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPemain" style="background-color:var(--hijau);">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pemain Baru
                    </button>
                </div>
            </div>

            <!-- TAB NAVIGASI PER KATEGORI USIA -->
            @php
                $groupedPlayers = $players->groupBy('age_category_id');
            @endphp

            <ul class="nav nav-pills mb-3" id="playerTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold small" id="tab-all-tab" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab">
                        <i class="bi bi-people me-1"></i> Semua Kategori ({{ $players->count() }})
                    </button>
                </li>
                @foreach($categories as $cat)
                    @php
                        $catCount = $groupedPlayers->get($cat->id, collect())->count();
                    @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold small" id="tab-cat-{{ $cat->id }}-tab" data-bs-toggle="pill" data-bs-target="#tab-cat-{{ $cat->id }}" type="button" role="tab">
                            <i class="bi bi-trophy me-1"></i> Kategori {{ $cat->name }} ({{ $catCount }} Pemain)
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- CONTENT TAB -->
            <div class="tab-content" id="playerTabContent">
                <!-- TAB 1: SEMUA PEMAIN -->
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    @include('operator.partials.player_table', ['playerGroup' => $players, 'categoryLabel' => 'Semua Kategori'])
                </div>

                <!-- TAB PER KATEGORI -->
                @foreach($categories as $cat)
                    @php
                        $catPlayers = $groupedPlayers->get($cat->id, collect());
                    @endphp
                    <div class="tab-pane fade" id="tab-cat-{{ $cat->id }}" role="tabpanel">
                        @include('operator.partials.player_table', ['playerGroup' => $catPlayers, 'categoryLabel' => $cat->name])
                    </div>
                @endforeach
            </div>

        </main>
    </div>
</div>

<!-- MODAL TAMBAH PEMAIN -->
<div class="modal fade" id="modalTambahPemain" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('operator.datapemain.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header text-white" style="background-color:var(--hijau-tua);">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pemain Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">

                    <div class="doc-section-title mb-3">
                        <i class="bi bi-person-vcard me-1"></i> 1. Data Identitas Pemain
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small">Nama Lengkap Pemain <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Sesuai Akta / KK" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kategori Usia <span class="text-danger">*</span></label>
                            <select name="age_category_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} (Maks. Lahir {{ $cat->max_birth_year }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" maxlength="16" placeholder="16 Digit Angka NIK" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" placeholder="Kebumen">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nomor Punggung <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="number" name="jersey_number" class="form-control" min="1" max="99" placeholder="Opsional (1–99)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Posisi Bermain <span class="text-danger">*</span></label>
                            <select name="position" class="form-select" required>
                                <option value="Penyerang">Penyerang (Striker)</option>
                                <option value="Gelandang">Gelandang (Midfielder)</option>
                                <option value="Bek">Bek (Defender)</option>
                                <option value="Kiper">Kiper (Goalkeeper)</option>
                            </select>
                        </div>
                    </div>

                    <div class="doc-section-title mb-3 mt-4">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i> 2. Unggah Berkas & Pas Foto (Foto Langsung / Galeri)
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Pas Foto Pemain (3x4)</label>
                            <input type="file" name="file_foto" class="form-control" accept="image/*">
                            <small class="text-muted" style="font-size:.7rem;">Format JPG/PNG. Maks 3MB.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Akta Kelahiran</label>
                            <input type="file" name="file_akta" class="form-control" accept="image/*,application/pdf">
                            <small class="text-muted" style="font-size:.7rem;">Foto/Scan Akta. Maks 3MB.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kartu Keluarga (KK)</label>
                            <input type="file" name="file_kk" class="form-control" accept="image/*,application/pdf">
                            <small class="text-muted" style="font-size:.7rem;">Foto/Scan KK. Maks 3MB.</small>
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-light mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark small"><i class="bi bi-file-earmark-check text-success me-1"></i> Dokumen Pendukung Identitas Pemain</span>
                            <span class="badge bg-warning text-dark">Wajib Upload Minimal 1 (Pilih Salah Satu)</span>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-bold small">Pilih Jenis Dokumen Pendukung <span class="text-danger">*</span></label>
                                <select name="supporting_doc_type" class="form-select">
                                    <option value="kia" selected>KIA (Kartu Identitas Anak)</option>
                                    <option value="ijazah">Ijazah Terakhir / SKL</option>
                                    <option value="nisn">Bukti / Screenshot NISN</option>
                                    <option value="raport">Raport Semester Terakhir</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-bold small">Upload File Dokumen Pendukung (Kamera / Galeri) <span class="text-danger">*</span></label>
                                <input type="file" name="file_supporting" class="form-control" accept="image/*,application/pdf">
                                <small class="text-muted" style="font-size:.7rem;">Cukup pilih 1 jenis dokumen di samping dan unggah foto/scan berkasnya (Maks 3MB).</small>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold" style="background-color:var(--hijau);"><i class="bi bi-download me-1"></i> Simpan & Kirim Ke Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODALS EDIT PEMAIN -->
@foreach($players as $p)
    @php
        $pdocs = $p->documents->keyBy('type');
    @endphp
    <div class="modal fade" id="modalEditPemain{{ $p->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('operator.datapemain.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header text-white" style="background-color:var(--hijau-tua);">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Pemain: {{ $p->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">

                        <div class="doc-section-title mb-3">
                            <i class="bi bi-person-vcard me-1"></i> 1. Identitas Pemain
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold small">Nama Lengkap Pemain</label>
                                <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Kategori Usia</label>
                                <select name="age_category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $p->age_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">NIK</label>
                                <input type="text" name="nik" class="form-control" maxlength="16" value="{{ $p->nik }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="form-control" value="{{ $p->birth_date?->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Tempat Lahir</label>
                                <input type="text" name="birth_place" class="form-control" value="{{ $p->birth_place }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Nomor Punggung <span class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="number" name="jersey_number" class="form-control" value="{{ $p->jersey_number }}" min="1" max="99">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Posisi Bermain</label>
                                <select name="position" class="form-select" required>
                                    <option value="Penyerang" {{ $p->position === 'Penyerang' ? 'selected' : '' }}>Penyerang</option>
                                    <option value="Gelandang" {{ $p->position === 'Gelandang' ? 'selected' : '' }}>Gelandang</option>
                                    <option value="Bek" {{ $p->position === 'Bek' ? 'selected' : '' }}>Bek</option>
                                    <option value="Kiper" {{ $p->position === 'Kiper' ? 'selected' : '' }}>Kiper</option>
                                </select>
                            </div>
                        </div>

                        <div class="doc-section-title mb-3 mt-4">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i> 2. Unggah Ulang Dokumen (Biarkan Kosong Jika Tidak Diganti)
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Pas Foto (3x4)</label>
                                <input type="file" name="file_foto" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Akta Kelahiran</label>
                                <input type="file" name="file_akta" class="form-control" accept="image/*,application/pdf">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Kartu Keluarga (KK)</label>
                                <input type="file" name="file_kk" class="form-control" accept="image/*,application/pdf">
                            </div>
                        <div class="border rounded p-3 bg-light mt-3">
                            <div class="fw-bold text-dark small mb-2"><i class="bi bi-file-earmark-check text-success me-1"></i> Ganti / Unggah Dokumen Pendukung (Opsional)</div>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold small">Jenis Dokumen Pendukung</label>
                                    <select name="supporting_doc_type" class="form-select">
                                        <option value="kia">KIA (Kartu Identitas Anak)</option>
                                        <option value="ijazah">Ijazah Terakhir / SKL</option>
                                        <option value="nisn">Bukti / Screenshot NISN</option>
                                        <option value="raport">Raport Semester Terakhir</option>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small">Upload File Dokumen Pendukung Baru</label>
                                    <input type="file" name="file_supporting" class="form-control" accept="image/*,application/pdf">
                                    <small class="text-muted" style="font-size:.7rem;">Kosongkan jika tidak ingin mengganti dokumen pendukung yang ada.</small>
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm fw-bold" style="background-color:var(--hijau);"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
