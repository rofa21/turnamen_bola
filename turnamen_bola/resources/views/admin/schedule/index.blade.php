<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pertandingan - Panitia Pusat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        .pagination svg, nav svg, svg.w-5, svg.h-5 { width: 1.25rem !important; height: 1.25rem !important; display: inline-block; }
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.schedule.index') }}"><i class="bi bi-calendar-check me-2"></i> Jadwal Pertandingan</a></li>
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
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill me-1"></i>Gagal menyimpan jadwal:</strong>
                    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-calendar-check text-primary me-2"></i> Pengelolaan Jadwal Pertandingan</h2>
                    <p class="text-muted mb-0">Atur bagan, waktu kick-off, lokasi stadion, dan publikasi jadwal tanding turnamen.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                        <i class="bi bi-plus-circle-fill me-1"></i> Buat Jadwal Baru
                    </button>
                </div>
            </div>

            <!-- FILTER & PENCARIAN -->
            <form action="{{ route('admin.schedule.index') }}" method="GET" class="card border-0 shadow-sm p-3 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Filter Kategori Usia</label>
                        <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Kategori Usia</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">Pencarian Tim</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama SSB yang bertanding...">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- TABEL DAFTAR JADWAL -->
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-task text-secondary me-2"></i> Output Bagan & Jadwal Aktif</h5>
                    <span class="badge bg-secondary p-2">Total: {{ $schedules->total() }} Pertandingan</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="15%">Kategori & Babak</th>
                                <th width="35%">Tim Bertanding (Home vs Away)</th>
                                <th width="20%">Waktu & Tanggal</th>
                                <th width="15%">Lokasi Lapangan</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $index => $sch)
                                <tr>
                                    <td class="text-center">{{ $schedules->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark mb-1">{{ $sch->ageCategory?->name }}</span><br>
                                        <small class="text-muted">{{ $sch->round_label }} {{ $sch->group_name ? '('.$sch->group_name.')' : '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $sch->homeTeam?->name ?? 'TBD' }}</div>
                                        <small class="text-muted">vs</small>
                                        <div class="fw-bold text-dark">{{ $sch->awayTeam?->name ?? 'TBD' }}</div>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-event me-1 text-primary"></i> {{ $sch->match_date ? $sch->match_date->format('d M Y') : '-' }}<br>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $sch->match_time }} WIB</small>
                                    </td>
                                    <td><span class="small">{{ $sch->location }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditJadwal{{ $sch->id }}"><i class="bi bi-pencil-square"></i></button>
                                        <form action="{{ route('admin.schedule.destroy', $sch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada jadwal pertandingan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $schedules->links() }}
                </div>
            </div>

        </main>
    </div>
</div>

<!-- MODALS OUTSIDE TABLE -->
@foreach($schedules as $sch)
    <div class="modal fade" id="modalEditJadwal{{ $sch->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.schedule.update', $sch->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Jadwal Pertandingan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Kategori Usia</label>
                                <select name="age_category_id" class="form-select">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $sch->age_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Babak / Fase Turnamen</label>
                                <select name="round" class="form-select">
                                    <option value="penyisihan" {{ $sch->round == 'penyisihan' ? 'selected' : '' }}>Babak Penyisihan Grup</option>
                                    <option value="8besar" {{ $sch->round == '8besar' ? 'selected' : '' }}>Babak 8 Besar</option>
                                    <option value="semifinal" {{ $sch->round == 'semifinal' ? 'selected' : '' }}>Semifinal</option>
                                    <option value="final" {{ $sch->round == 'final' ? 'selected' : '' }}>Final</option>
                                    <option value="perebutan_juara3" {{ $sch->round == 'perebutan_juara3' ? 'selected' : '' }}>Perebutan Juara 3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tim Home</label>
                                <input type="text" class="form-control" value="{{ $sch->homeTeam?->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tim Away</label>
                                <input type="text" class="form-control" value="{{ $sch->awayTeam?->name }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Tanggal Pertandingan</label>
                                <input type="date" name="match_date" class="form-control" value="{{ $sch->match_date ? $sch->match_date->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Jam Kick-Off</label>
                                <input type="time" name="match_time" class="form-control" value="{{ $sch->match_time }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Lokasi / Stadion</label>
                                <input type="text" name="location" class="form-control" value="{{ $sch->location }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Status Pertandingan</label>
                                <select name="status" class="form-select">
                                    <option value="scheduled" {{ $sch->status == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    <option value="ongoing" {{ $sch->status == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                                    <option value="finished" {{ $sch->status == 'finished' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $sch->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Skor Home</label>
                                <input type="number" name="home_score" class="form-control" value="{{ $sch->home_score }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Skor Away</label>
                                <input type="number" name="away_score" class="form-control" value="{{ $sch->away_score }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Perbarui Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- MODAL BUAT JADWAL BARU -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.schedule.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus text-warning me-2"></i> Input Jadwal Pertandingan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Kelompok Usia (Kategori)</label>
                            <select name="age_category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Babak / Fase Turnamen</label>
                            <select name="round" class="form-select" required>
                                <option value="penyisihan">Babak Penyisihan Grup</option>
                                <option value="8besar">Babak 8 Besar</option>
                                <option value="semifinal">Semifinal</option>
                                <option value="final">Final</option>
                                <option value="perebutan_juara3">Perebutan Juara 3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Nama Grup (Opsional)</label>
                            <input type="text" name="group_name" class="form-control" placeholder="Contoh: Grup A, Grup B">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tim Pertama (Home)</label>
                            <select name="home_team_id" class="form-select" required id="homeTeamSelect">
                                <option value="">-- Pilih SSB Peserta --</option>
                                @foreach($teams as $tm)
                                    <option value="{{ $tm->id }}">{{ $tm->name }} ({{ $tm->ageCategory?->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Tim Kedua (Away)</label>
                            <select name="away_team_id" class="form-select" required id="awayTeamSelect">
                                <option value="">-- Pilih SSB Peserta --</option>
                                @foreach($teams as $tm)
                                    <option value="{{ $tm->id }}">{{ $tm->name }} ({{ $tm->ageCategory?->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Tanggal Pertandingan</label>
                            <input type="date" name="match_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Jam Kick-Off</label>
                            <input type="time" name="match_time" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Lokasi / Stadion</label>
                            <input type="text" name="location" class="form-control" value="Stadion Chandradimuka Kebumen" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
