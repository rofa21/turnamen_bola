<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin - Piala Disdikpora Grassroot Regional Kebumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        .stat-card { border: none; border-radius: 10px; transition: transform 0.2s; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .stat-card:hover { transform: translateY(-3px); }
        .chart-container { position: relative; height: 260px; width: 100%; }
        .top-banner {
            width: 100%;
            aspect-ratio: 1024 / 341;
            background: url('/images/banner-turnamen.jpg') center center / cover no-repeat;
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding: 24px 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.18);
            border: 2px solid #d4a017;
        }
        .top-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.4) 65%, transparent 100%);
        }
        .top-banner .content { position: relative; z-index: 1; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR SUPER ADMIN -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="text-center py-3 border-bottom border-secondary mb-3">
                <img src="{{ $activeEvent->logo_url ?? '/images/logo-turnamen.jpg' }}" alt="Logo Panitia" class="rounded-circle border border-warning border-2 mb-2" style="width:52px;height:52px;object-fit:cover;">
                <h6 class="text-white fw-bold mt-1 mb-0">PANITIA PUSAT</h6>
                <small class="text-muted text-uppercase" style="font-size: 0.68rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $activeEvent->name }}</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator ({{ $totalOperators }})</a></li>
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

            <!-- TOP HEADER BANNER -->
            <div class="top-banner shadow-sm">
                <div class="content">
                    <h4 class="text-white fw-bold mb-1">
                        PANITIA PUSAT — <span class="text-warning">PIALA DISDIKPORA KEBUMEN</span>
                    </h4>
                    <p class="text-light mb-0 small">
                        <i class="bi bi-shield-check me-1"></i>Sistem Informasi Manajemen Turnamen & Verifikasi Berkas Pemain
                    </p>
                </div>
            </div>

            <!-- HEADER BAR -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard Super Admin</h2>
                    <p class="text-muted mb-0">Ringkasan real-time operasional turnamen Piala Disdikpora Grassroot Regional Kebumen.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form action="{{ route('admin.verify.auto_all') }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Verifikasi Umur Otomatis
                        </button>
                    </form>
                    <span class="badge bg-dark p-2 text-white shadow-sm"><i class="bi bi-calendar-event me-1"></i> Musim 2026 / 2027</span>
                </div>
            </div>

            <!-- STATISTIK KARTU UTAMA -->
            <div class="row g-3 mb-4">
                <!-- Total Tim -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-primary border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Tim Terdaftar</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalTeams }} <span class="fs-6 fw-normal text-muted">SSB</span></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded text-primary fs-4">
                                <i class="bi bi-shield-shaded"></i>
                            </div>
                        </div>
                        <small class="text-success mt-2 d-block"><i class="bi bi-check-circle-fill me-1"></i> SSB Terdaftar Terverifikasi</small>
                    </div>
                </div>

                <!-- Total Pemain -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-success border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Pemain</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalPlayers }} <span class="fs-6 fw-normal text-muted">Orang</span></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded text-success fs-4">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">Akumulasi KU-10 & KU-12</small>
                    </div>
                </div>

                <!-- Kategori KU-10 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-info border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Kategori Usia 10 (KU-10)</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $ku10Count }} <span class="fs-6 fw-normal text-muted">Pemain</span></h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded text-info fs-4">
                                <i class="bi bi-award-fill"></i>
                            </div>
                        </div>
                        <small class="text-info mt-2 d-block">Kelahiran 2016+</small>
                    </div>
                </div>

                <!-- Kategori KU-12 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-white p-3 border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Kategori Usia 12 (KU-12)</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $ku12Count }} <span class="fs-6 fw-normal text-muted">Pemain</span></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded text-warning fs-4">
                                <i class="bi bi-award-fill"></i>
                            </div>
                        </div>
                        <small class="text-dark mt-2 d-block">Kelahiran 2014+</small>
                    </div>
                </div>
            </div>

            <!-- ROW GRAFIK -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i> Grafik Tren Pendaftaran Peserta</h5>
                            <span class="badge bg-light text-secondary border">Real-time</span>
                        </div>
                        <p class="text-muted small mb-3">Akumulasi jumlah tim dan pemain yang masuk ke sistem per periode waktu.</p>
                        <div class="chart-container">
                            <canvas id="pesertaChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pie-chart-fill text-success me-2"></i> Rasio Kategori Usia</h5>
                        </div>
                        <p class="text-muted small mb-3">Perbandingan jumlah pemain KU-10 dan KU-12.</p>
                        <div class="chart-container d-flex justify-content-center align-items-center">
                            <canvas id="kategoriChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW PROGRES VERIFIKASI & JADWAL -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-check2-circle text-primary me-2"></i> Status Verifikasi Pemain Global</h5>
                        <p class="text-muted small">Persentase dokumen dan keabsahan pemain dari seluruh SSB.</p>
                        
                        <div class="mb-3 mt-3">
                            <div class="d-flex justify-content-between fw-bold mb-1">
                                <span>Lolos Verifikasi ({{ $approvedCount }} Pemain)</span>
                                <span class="text-success">{{ $approvedPercent }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $approvedPercent }}%;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between fw-bold mb-1">
                                <span>Menunggu Verifikasi ({{ $pendingCount }} Pemain)</span>
                                <span class="text-warning">{{ $pendingPercent }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingPercent }}%;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between fw-bold mb-1">
                                <span>Ditolak / Perlu Revisi ({{ $rejectedCount }} Pemain)</span>
                                <span class="text-danger">{{ $rejectedPercent }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $rejectedPercent }}%;"></div>
                            </div>
                        </div>

                        <div class="mt-auto pt-3">
                            <a href="{{ route('admin.teams.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-arrow-right-circle me-1"></i> Kelola & Verifikasi Pemain</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-check-fill text-warning me-2"></i> Jadwal Pertandingan Terdekat</h5>
                            <a href="{{ route('admin.schedule.index') }}" class="btn btn-sm btn-outline-secondary">Kelola Jadwal</a>
                        </div>
                        <p class="text-muted small">Daftar pertandingan pembuka yang akan segera dilaksanakan.</p>
                        
                        <div class="list-group list-group-flush">
                            @forelse($recentMatches as $match)
                                <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-info text-dark mb-1">{{ $match->ageCategory?->name }} • {{ $match->round_label }}</span>
                                        <h6 class="mb-0 fw-bold">{{ $match->homeTeam?->name }} vs {{ $match->awayTeam?->name }}</h6>
                                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $match->location }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-dark">{{ $match->match_time }} WIB</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">Belum ada jadwal pertandingan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL AKTIVITAS TERBARU -->
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-secondary me-2"></i> Pemain Baru Terdaftar</h5>
                    <a href="{{ route('admin.teams.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Data</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Registrasi</th>
                                <th>Nama Pemain</th>
                                <th>Nama Tim (SSB)</th>
                                <th>Kategori</th>
                                <th>Status Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $p)
                                <tr>
                                    <td><code>{{ $p->registration_number ?? '-' }}</code></td>
                                    <td class="fw-bold">{{ $p->name }}</td>
                                    <td>{{ $p->team?->name }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $p->ageCategory?->name }}</span></td>
                                    <td>{!! $p->verification?->status_badge ?? '<span class="badge bg-secondary">Pending</span>' !!}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3">Belum ada data pemain terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctxPeserta = document.getElementById('pesertaChart').getContext('2d');
    new Chart(ctxPeserta, {
        type: 'bar',
        data: {
            labels: ['Tim Terdaftar', 'Pemain KU-10', 'Pemain KU-12', 'Total Pemain'],
            datasets: [{
                label: 'Jumlah Peserta',
                data: [{{ $totalTeams }}, {{ $ku10Count }}, {{ $ku12Count }}, {{ $totalPlayers }}],
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    const ctxKategori = document.getElementById('kategoriChart').getContext('2d');
    new Chart(ctxKategori, {
        type: 'doughnut',
        data: {
            labels: ['KU-10 ({{ $ku10Count }})', 'KU-12 ({{ $ku12Count }})'],
            datasets: [{
                data: [{{ $ku10Count }}, {{ $ku12Count }}],
                backgroundColor: ['#0dcaf0', '#ffc107'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
</body>
</html>
