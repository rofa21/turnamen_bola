<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Tim SSB - {{ $operator->name }}</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --hijau: #1a5c2a; --hijau-tua: #0f3b1a; --emas: #d4a017; }
        body { background-color: #f0f4f0; font-family: 'Segoe UI', sans-serif; }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--hijau-tua) 0%, var(--hijau) 100%);
        }
        .sidebar-brand { background: rgba(0,0,0,0.3); border-bottom: 2px solid var(--emas); }
        .sidebar .nav-link { color: #c8e6c9; margin-bottom: 3px; border-radius: 8px; transition: all 0.2s; padding: 8px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--emas) 0%, #b8870f 100%);
        }

        /* FACEBOOK-STYLE PROFILE */
        .profile-cover {
            height: 220px;
            background: url('/images/banner-turnamen.jpg') center center / cover no-repeat;
            border-radius: 12px 12px 0 0;
            position: relative;
        }
        .profile-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 60%);
            border-radius: 12px 12px 0 0;
        }
        .profile-logo-wrap {
            position: absolute;
            bottom: -50px;
            left: 28px;
            z-index: 2;
        }
        .profile-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #fff;
            object-fit: cover;
            background: #e9ecef;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .profile-logo-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #fff;
            background: linear-gradient(135deg, var(--hijau), var(--emas));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .profile-card { border-radius: 0 0 12px 12px; border: none; }
        .profile-info-bar { padding: 60px 24px 16px; border-bottom: 1px solid #e9ecef; }

        /* EDIT FORM PANEL */
        .form-hint { font-size: .75rem; color: #6c757d; margin-top: 4px; }
        .form-label { font-weight: 600; font-size: .85rem; }

        .btn-edit-toggle {
            background: linear-gradient(135deg, var(--hijau), #2e7d32);
            border: none;
            color: white;
            border-radius: 20px;
            padding: 6px 18px;
            font-size: .85rem;
            font-weight: 600;
        }
        .btn-edit-toggle:hover { filter: brightness(1.1); color: white; }

        .info-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f4f0;
            border: 1px solid #c8e6c9;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .82rem;
            color: var(--hijau);
            font-weight: 500;
        }
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
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.profile') }}"><i class="bi bi-building me-2"></i> Profil Tim SSB</a></li>
                <hr class="border-success my-2">
                <li class="nav-item">
                    <form action="{{ route('operator.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </button>
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal disimpan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- PROFILE CARD (FACEBOOK-STYLE) --}}
            <div class="card shadow-sm mb-4" style="border-radius:12px; border: none; overflow: visible;">
                {{-- COVER PHOTO --}}
                <div class="profile-cover">
                    <div class="profile-logo-wrap">
                        @if($team?->logo_url)
                            <img src="{{ $team->logo_url }}" alt="Logo Tim" class="profile-logo">
                        @else
                            <div class="profile-logo-placeholder">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- INFO BAR --}}
                <div class="profile-card bg-white px-3">
                    <div class="profile-info-bar">
                        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2">
                            <div>
                                <h4 class="fw-bold text-dark mb-1">{{ $team->name ?? $operator->name }}</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($team?->ageCategory)
                                        <span class="info-pill"><i class="bi bi-trophy"></i> {{ $team->ageCategory->name }}</span>
                                    @endif
                                    @if($operator->district)
                                        <span class="info-pill"><i class="bi bi-geo-alt"></i> Kec. {{ $operator->district }}</span>
                                    @endif
                                    @if($team)
                                        <span class="info-pill"><i class="bi bi-people"></i> {{ $team->players()->count() }} Pemain</span>
                                    @endif
                                </div>
                            </div>
                            {{-- TOMBOL EDIT --}}
                            <button class="btn btn-edit-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#formEditProfil" aria-expanded="false">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </button>
                        </div>
                    </div>

                    {{-- INFO DETAIL (READ-ONLY TAMPILAN) --}}
                    @if($team)
                    <div class="px-2 pb-3 pt-2">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bg-light rounded p-3">
                                    <div class="text-muted small fw-semibold mb-1"><i class="bi bi-person me-1"></i>Nama Manajer</div>
                                    <div class="fw-bold">{{ $team->manager_name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded p-3">
                                    <div class="text-muted small fw-semibold mb-1"><i class="bi bi-whatsapp me-1"></i>Nomor WhatsApp</div>
                                    <div class="fw-bold">{{ $team->manager_phone ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded p-3">
                                    <div class="text-muted small fw-semibold mb-1"><i class="bi bi-patch-check me-1"></i>Status Verifikasi</div>
                                    @php
                                        $approved = $team->players()->whereHas('verification', fn($q) => $q->whereIn('status',['approved','auto_approved']))->count();
                                        $total = $team->players()->count();
                                    @endphp
                                    <div class="fw-bold text-success">{{ $approved }}/{{ $total }} Pemain Lolos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- FORM EDIT (TERSEMBUNYI, MUNCUL SAAT KLIK TOMBOL EDIT) --}}
            <div class="collapse" id="formEditProfil">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom pt-3 pb-2">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-pencil-square text-success me-2"></i> Form Edit Profil Tim SSB
                        </h5>
                        <small class="text-muted">Lengkapi semua data dengan benar sesuai dokumen resmi SSB.</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('operator.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">

                                {{-- LOGO UPLOAD --}}
                                <div class="col-12">
                                    <label class="form-label"><i class="bi bi-image text-success me-1"></i> Logo Tim SSB</label>
                                    <div class="d-flex align-items-start gap-3">
                                        @if($team?->logo_url)
                                            <img src="{{ $team->logo_url }}" alt="Logo" style="width:64px;height:64px;object-fit:contain;border-radius:8px;border:1px solid #dee2e6;background:#f8f9fa;padding:4px;">
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="logo" class="form-control" accept="image/png,image/jpg,image/jpeg">
                                            <div class="form-hint">📌 Upload logo resmi SSB (PNG/JPG, maks. 2MB). Logo akan tampil di ID Card pemain dan buku tim.</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- NAMA SSB --}}
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-building me-1"></i> Nama Resmi SSB</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $team->name ?? $operator->name) }}" required placeholder="Contoh: SSB Garuda Muda Kebumen">
                                    <div class="form-hint">📌 Tulis nama lengkap resmi Sekolah Sepak Bola Anda.</div>
                                </div>

                                {{-- KECAMATAN --}}
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-geo-alt me-1"></i> Kecamatan Domisili</label>
                                    <input type="text" name="district" class="form-control" value="{{ old('district', $team->district ?? $operator->district) }}" required placeholder="Contoh: Kebumen / Sruweng / Pejagoan">
                                    <div class="form-hint">📌 Kecamatan tempat SSB berdomisili/berlatih.</div>
                                </div>

                                {{-- KATEGORI USIA --}}
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-trophy me-1"></i> Kategori Kelompok Usia</label>
                                    <select name="age_category_id" class="form-select" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ ($team->age_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }} — Batas Kelahiran {{ $cat->max_birth_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint">📌 Pilih kategori usia sesuai usia rata-rata pemain SSB Anda.</div>
                                </div>

                                {{-- NAMA MANAJER --}}
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-person me-1"></i> Nama Manajer / Penanggung Jawab</label>
                                    <input type="text" name="manager_name" class="form-control" value="{{ old('manager_name', $team->manager_name ?? $operator->pic_name) }}" required placeholder="Nama lengkap manajer SSB">
                                    <div class="form-hint">📌 Nama orang yang bertanggung jawab atas tim di turnamen ini.</div>
                                </div>

                                {{-- NOMOR WA --}}
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-whatsapp me-1"></i> Nomor WhatsApp Aktif</label>
                                    <input type="text" name="manager_phone" class="form-control" value="{{ old('manager_phone', $team->manager_phone ?? $operator->phone) }}" required placeholder="Contoh: 08123456789">
                                    <div class="form-hint">📌 Nomor WA aktif manajer, dihubungi panitia jika ada kendala.</div>
                                </div>

                                {{-- JERSEY COLOR (tersembunyi, isi default) --}}
                                <input type="hidden" name="jersey_color" value="{{ $team->jersey_color ?? 'Belum Diisi' }}">

                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success px-4 fw-bold">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#formEditProfil">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
@if($errors->any())
<script>
    // Buka form jika ada error validasi
    var el = document.getElementById('formEditProfil');
    if (el) { var bsCollapse = new bootstrap.Collapse(el, {show: true}); }
</script>
@endif
</body>
</html>
