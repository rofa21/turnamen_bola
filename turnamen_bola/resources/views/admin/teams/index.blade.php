<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tim & Verifikasi Pemain - Panitia Pusat</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        .team-logo { width:44px;height:44px;object-fit:cover;border:2px solid #dee2e6;border-radius:50%; }
        .team-logo-placeholder { width:44px;height:44px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center; }
        .nav-pills .nav-link.active { background-color: #0d6efd; }
        .nav-pills .nav-link { color: #212529; background: #e9ecef; margin-right: 6px; margin-bottom: 6px; }
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
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.teams.index') }}"><i class="bi bi-shield-shaded me-2"></i> Data Tim & Verifikasi</a></li>
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
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-shield-shaded text-primary me-2"></i> Manajemen Tim & Verifikasi Pemain</h2>
                    <p class="text-muted mb-0">Klik tombol Squad per SSB untuk memeriksa pemain & verifikasi berkas. Data dikelompokkan per kategori umur.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form action="{{ route('admin.verify.auto_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Verifikasi Otomatis Dari Tgl Lahir
                        </button>
                    </form>
                </div>
            </div>

            <!-- FILTER & PENCARIAN -->
            <form action="{{ route('admin.teams.index') }}" method="GET" class="card border-0 shadow-sm p-3 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Filter Kategori</label>
                        <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Kategori Usia</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} (Maks. {{ $cat->max_birth_year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">Pencarian Tim (SSB)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama SSB atau kecamatan...">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- TABS PER KATEGORI -->
            @php
                $allCats = $categories;
            @endphp
            <ul class="nav nav-pills mb-3" id="categoryTab">
                <li class="nav-item">
                    <button class="nav-link fw-bold active" data-bs-toggle="pill" data-bs-target="#tab-all-teams">
                        <i class="bi bi-grid me-1"></i> Semua Tim ({{ $teams->total() }})
                    </button>
                </li>
                @foreach($allCats as $cat)
                    @php $catCount = $teams->filter(fn($t) => $t->age_category_id == $cat->id)->count(); @endphp
                    <li class="nav-item">
                        <button class="nav-link fw-bold" data-bs-toggle="pill" data-bs-target="#tab-cat-{{ $cat->id }}">
                            <i class="bi bi-trophy me-1"></i> {{ $cat->name }} ({{ $catCount }} Tim)
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                {{-- TAB SEMUA TIM --}}
                <div class="tab-pane fade show active" id="tab-all-teams">
                    @include('admin.teams.partials.team_table', ['teamGroup' => $teams, 'teams' => $teams])
                </div>

                {{-- TAB PER KATEGORI --}}
                @foreach($allCats as $cat)
                    @php $catTeams = $teams->filter(fn($t) => $t->age_category_id == $cat->id); @endphp
                    <div class="tab-pane fade" id="tab-cat-{{ $cat->id }}">
                        @include('admin.teams.partials.team_table', ['teamGroup' => $catTeams, 'teams' => $teams])
                    </div>
                @endforeach
            </div>

            <div class="mt-3">{{ $teams->links() }}</div>

        </main>
    </div>
</div>

<!-- ALL MODALS PLACED OUTSIDE THE TABLE -->
@foreach($teams as $team)
    <div class="modal fade" id="modalDetailTim{{ $team->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <div>
                        <h5 class="modal-title fw-bold"><i class="bi bi-shield-shaded me-2 text-warning"></i> Squad Pemain: {{ $team->name }}</h5>
                        <small class="text-light opacity-75">Pemeriksaan Dokumen & Auto-Verifikasi Umur Dari Tgl Lahir</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <strong>Manajer Tim:</strong> {{ $team->manager_name ?? '-' }} | <strong>Kontak:</strong> {{ $team->manager_phone ?? '-' }} | <strong>Jersey:</strong> {{ $team->jersey_color ?? '-' }}
                        </div>
                        <span class="badge bg-dark">Kategori: {{ $team->ageCategory?->name }}</span>
                    </div>

                    @php
                        $squadCats = $team->players->groupBy('age_category_id');
                        $allCatsList = \App\Models\AgeCategory::whereIn('id', $squadCats->keys())->get()->keyBy('id');
                    @endphp

                    @if($allCatsList->count() > 1)
                        {{-- Jika pemain ada di lebih dari 1 kategori, tampilkan tabs --}}
                        <ul class="nav nav-pills mb-3 border-bottom pb-2" id="squadTabs{{ $team->id }}">
                            @foreach($allCatsList as $cId => $cObj)
                                <li class="nav-item">
                                    <button class="nav-link fw-semibold {{ $loop->first ? 'active' : '' }}"
                                        data-bs-toggle="pill"
                                        data-bs-target="#squadTab{{ $team->id }}_{{ $cId }}">
                                        <i class="bi bi-trophy me-1"></i> {{ $cObj->name }}
                                        <span class="badge bg-white text-dark ms-1">{{ $squadCats[$cId]->count() }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($allCatsList as $cId => $cObj)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="squadTab{{ $team->id }}_{{ $cId }}">
                                    @include('admin.teams.partials.player_row_table', ['players' => $squadCats[$cId], 'catLabel' => $cObj->name])
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Hanya satu kategori --}}
                        @include('admin.teams.partials.player_row_table', ['players' => $team->players, 'catLabel' => $team->ageCategory?->name])
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.print.index', ['team_id' => $team->id, 'type' => 'buku-tim']) }}" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Cetak Manifes Tim Ini</a>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

