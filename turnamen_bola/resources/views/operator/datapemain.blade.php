<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemain - {{ $operator->name }}</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --hijau: #1a5c2a; --hijau-tua: #0f3b1a; --emas: #d4a017; }
        body { background-color: #f0f4f0; font-family: 'Segoe UI', sans-serif; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, var(--hijau-tua) 0%, var(--hijau) 100%); }
        .sidebar-brand { background: rgba(0,0,0,0.3); border-bottom: 2px solid var(--emas); }
        .sidebar .nav-link { color: #c8e6c9; margin-bottom: 3px; border-radius: 8px; transition: all 0.2s; padding: 8px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: linear-gradient(135deg, var(--emas) 0%, #b8870f 100%); }
        .doc-badge { font-size: .68rem; padding: 2px 6px; }
        .doc-section-title { background: #f8f9fa; border-left: 3px solid var(--hijau); padding: 6px 12px; border-radius: 4px; font-size: .82rem; font-weight: 600; color: var(--hijau); }
        .nav-pills .nav-link.active { background-color: var(--hijau); color: white; }
        .nav-pills .nav-link { color: var(--hijau-tua); background-color: #e8f5e9; margin-right: 6px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
            <div class="sidebar-brand text-center p-3">
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
                    <strong>Gagal menyimpan:</strong>
                    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between flex-wrap align-items-center pt-2 pb-3 mb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-success me-2"></i> Kelola Squad Pemain</h4>
                    <small class="text-muted">Input data pemain, unggah berkas dokumen, dan pantau verifikasi Admin Panitia.</small>
                </div>
                <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPemain">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Pemain Baru
                </button>
            </div>

            <!-- TABS PEMISAH KATEGORI UMUR (KU-10, KU-12, DSK) -->
            <ul class="nav nav-pills mb-3" id="kategoriTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" id="all-tab" data-bs-toggle="pill" data-bs-target="#tab-all" type="button">
                        <i class="bi bi-people me-1"></i> Semua Kategori ({{ $players->count() }})
                    </button>
                </li>
                @foreach($categories as $cat)
                    @php $catCount = $players->where('age_category_id', $cat->id)->count(); @endphp
                    <li class="nav-item">
                        <button class="nav-link fw-bold" id="cat-{{ $cat->id }}-tab" data-bs-toggle="pill" data-bs-target="#tab-cat-{{ $cat->id }}" type="button">
                            <i class="bi bi-trophy me-1"></i> Kategori {{ $cat->name }} ({{ $catCount }} Pemain)
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="kategoriTabContent">
                <!-- TAB SEMUA PEMAIN -->
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    @include('operator.partials.player_table', ['playerGroup' => $players])
                </div>

                <!-- TAB PER KATEGORI UMUR -->
                @foreach($categories as $cat)
                    @php $catPlayers = $players->where('age_category_id', $cat->id); @endphp
                    <div class="tab-pane fade" id="tab-cat-{{ $cat->id }}" role="tabpanel">
                        @include('operator.partials.player_table', ['playerGroup' => $catPlayers])
                    </div>
                @endforeach
            </div>

        </main>
    </div>
</div>

<!-- ============================
     MODAL TAMBAH PEMAIN
============================= -->
<div class="modal fade" id="modalTambahPemain" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('operator.datapemain.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Tambah Data Pemain Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- DATA PRIBADI --}}
                    <div class="doc-section-title mb-3"><i class="bi bi-person-badge me-1"></i> Data Pribadi Pemain</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Usia Turnamen</label>
                            <select name="age_category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori KU --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} (Maks. Kelahiran {{ $cat->max_birth_year }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Lengkap Pemain</label>
                            <input type="text" name="name" class="form-control" placeholder="Sesuai Akta Kelahiran / KK" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">NIK (16 Digit)</label>
                            <input type="text" name="nik" class="form-control" placeholder="3305xxxxxxxxxxxxxx" maxlength="20" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="Kebumen">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nomor Punggung <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="number" name="jersey_number" class="form-control" min="1" max="99" placeholder="Opsional (1–99)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Posisi Pemain</label>
                            <select name="position" class="form-select" required>
                                <option value="Kiper">Kiper</option>
                                <option value="Bek">Bek</option>
                                <option value="Gelandang">Gelandang</option>
                                <option value="Penyerang" selected>Penyerang</option>
                            </select>
                        </div>
                    </div>

                    {{-- DOKUMEN UTAMA --}}
                    <div class="doc-section-title mb-3"><i class="bi bi-file-earmark-arrow-up me-1"></i> Dokumen Utama</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Pas Foto Pemain <span class="text-danger">*</span></label>
                            <input type="file" name="file_foto" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted">Ambil foto via Kamera atau Galeri HP.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Scan Akta Kelahiran</label>
                            <input type="file" name="file_akta" class="form-control form-control-sm" accept="image/*,.pdf">
                            <small class="text-muted">Foto / PDF Akta Kelahiran.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Scan Kartu Keluarga (KK)</label>
                            <input type="file" name="file_kk" class="form-control form-control-sm" accept="image/*,.pdf">
                            <small class="text-muted">Foto / PDF Halaman KK.</small>
                        </div>
                    </div>

                    {{-- DOKUMEN PENDUKUNG (OPSIONAL TAPI WAJIB PILIH MINIMAL 1) --}}
                    <div class="doc-section-title mb-2 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-file-earmark-check me-1"></i> Dokumen Pendukung Identitas</span>
                        <span class="badge bg-warning text-dark">Wajib Unggah Minimal Salah Satu (1)</span>
                    </div>

                    <div class="alert alert-warning border-warning d-flex align-items-center py-2 px-3 mb-3" style="font-size: 0.82rem;">
                        <i class="bi bi-info-circle-fill fs-5 me-2 text-warning"></i>
                        <div>
                            <strong>Petunjuk Pilihan Dokumen Pendukung:</strong> Tidak wajib diisi semuanya. Anda <u>cukup memilih salah satu</u> dari 4 dokumen berikut: <strong>KIA, Ijazah, NISN, atau Raport</strong>.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">KIA (Kartu Identitas Anak)</label>
                            <input type="file" name="file_kia" class="form-control form-control-sm" accept="image/*,.pdf">
                            <small class="text-muted">Pilihan 1: Foto / Scan KIA.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Ijazah Terakhir</label>
                            <input type="file" name="file_ijazah" class="form-control form-control-sm" accept="image/*,.pdf">
                            <small class="text-muted">Pilihan 2: Foto / Scan Ijazah.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Bukti NISN</label>
                            <input type="file" name="file_nisn" class="form-control form-control-sm" accept="image/*,.pdf">
                            <small class="text-muted">Pilihan 3: Bukti / Screenshot NISN.</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Raport Semester Terakhir</label>
                            <input type="file" name="file_raport" class="form-control form-control-sm" accept="image/*,.pdf">
                            <small class="text-muted">Pilihan 4: Foto Halaman Raport.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-4"><i class="bi bi-save me-1"></i> Simpan & Kirim Ke Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================
     MODALS EDIT PEMAIN
============================= -->
@foreach($players as $p)
@php $docs = $p->documents->keyBy('type'); @endphp
<div class="modal fade" id="modalEditPemain{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('operator.datapemain.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Data Pemain: {{ $p->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- DATA PRIBADI --}}
                    <div class="doc-section-title mb-3"><i class="bi bi-person-badge me-1"></i> Data Pribadi</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori Usia Turnamen</label>
                            <select name="age_category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $p->age_category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} (Maks. {{ $cat->max_birth_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ $p->nik }}" maxlength="20" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ $p->birth_date?->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="{{ $p->birth_place }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nomor Punggung <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="number" name="jersey_number" class="form-control" value="{{ $p->jersey_number }}" min="1" max="99">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Posisi</label>
                            <select name="position" class="form-select" required>
                                @foreach(['Kiper','Bek','Gelandang','Penyerang'] as $pos)
                                    <option value="{{ $pos }}" {{ $p->position == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- UPDATE DOKUMEN --}}
                    <div class="doc-section-title mb-2"><i class="bi bi-arrow-repeat me-1"></i> Update Dokumen Pemain</div>
                    <div class="alert alert-info py-2 px-3 mb-3 small">
                        <strong>Petunjuk:</strong> Kosongkan jika tidak ingin mengganti file. Pastikan minimal salah satu dokumen pendukung (KIA/Ijazah/NISN/Raport) telah terunggah.
                    </div>

                    <div class="row g-3 mb-3">
                        @foreach([
                            'file_foto' => ['foto','Pas Foto'],
                            'file_akta' => ['akta','Akta Kelahiran'],
                            'file_kk' => ['kk','Kartu Keluarga'],
                        ] as $field => [$type, $label])
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">
                                {{ $label }}
                                <span class="badge {{ $docs->has($type) ? 'bg-success' : 'bg-secondary' }} ms-1 small">{{ $docs->has($type) ? '✓ Ada' : '✗ Belum' }}</span>
                            </label>
                            <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*,.pdf">
                        </div>
                        @endforeach
                    </div>
                    <div class="row g-3">
                        @foreach([
                            'file_kia' => ['kia','KIA'],
                            'file_ijazah' => ['ijazah','Ijazah'],
                            'file_nisn' => ['nisn','NISN'],
                            'file_raport' => ['raport','Raport'],
                        ] as $field => [$type, $label])
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">
                                {{ $label }}
                                <span class="badge {{ $docs->has($type) ? 'bg-success' : 'bg-secondary' }} ms-1 small">{{ $docs->has($type) ? '✓' : '✗' }}</span>
                            </label>
                            <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*,.pdf">
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm px-4"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('modalTambahPemain'));
        modal.show();
    });
</script>
@endif
</body>
</html>
