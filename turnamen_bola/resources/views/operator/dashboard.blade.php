<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator SSB - Piala Disdikpora Grassroot Kebumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --hijau: #1a5c2a;
            --hijau-tua: #0f3b1a;
            --emas: #d4a017;
            --emas-terang: #f5c842;
        }
        body { background-color: #f0f4f0; font-family: 'Segoe UI', sans-serif; }

        /* SIDEBAR */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--hijau-tua) 0%, var(--hijau) 100%);
            color: #fff;
            z-index: 100;
        }
        .sidebar-brand { background: rgba(0,0,0,0.3); border-bottom: 2px solid var(--emas); padding: 12px 8px; }
        .sidebar-brand img { width: 48px; height: 48px; object-fit: contain; }
        .sidebar .nav-link { color: #c8e6c9; margin-bottom: 3px; border-radius: 8px; transition: all 0.2s; padding: 8px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--emas) 0%, #b8870f 100%);
        }
        .sidebar .nav-link i { width: 20px; }

        /* TOP BANNER (DYNAMIC ASPECT RATIO) */
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
            border: 2px solid var(--emas);
        }
        .top-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15,59,26,0.9) 0%, rgba(15,59,26,0.4) 40%, transparent 100%);
        }
        .top-banner .content { position: relative; z-index: 1; }

        /* STAT CARDS */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .stat-card .card-accent { position: absolute; top: 0; left: 0; width: 5px; height: 100%; }

        /* SHORTCUT CARDS */
        .shortcut-card {
            border: none;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s;
            display: block;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .shortcut-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .shortcut-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 12px;
        }
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.profile') }}"><i class="bi bi-building me-2"></i> Profil Tim SSB</a></li>
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
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- TOP BANNER --}}
            <div class="top-banner shadow">
                <div class="content">
                    <h4 class="text-white fw-bold mb-1">
                        Selamat Datang, <span class="text-warning">{{ $operator->name }}</span>
                    </h4>
                    <p class="text-light mb-0 small">
                        <i class="bi bi-geo-alt me-1"></i>{{ $operator->district ?? 'Kebumen' }} &nbsp;•&nbsp;
                        <i class="bi bi-calendar me-1"></i>{{ now()->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            {{-- STAT CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card p-3">
                        <div class="card-accent bg-primary"></div>
                        <div class="d-flex justify-content-between align-items-center ps-2">
                            <div>
                                <p class="text-muted small mb-1 fw-semibold">Total Pemain</p>
                                <h2 class="fw-bold text-dark mb-0">{{ $playersCount }}</h2>
                                <small class="text-muted">Pemain Terdaftar</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary fs-3">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card p-3">
                        <div class="card-accent bg-success"></div>
                        <div class="d-flex justify-content-between align-items-center ps-2">
                            <div>
                                <p class="text-muted small mb-1 fw-semibold">Lolos Verifikasi</p>
                                <h2 class="fw-bold text-success mb-0">{{ $approvedCount }}</h2>
                                <small class="text-muted">Pemain Disetujui</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success fs-3">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card p-3">
                        <div class="card-accent bg-warning"></div>
                        <div class="d-flex justify-content-between align-items-center ps-2">
                            <div>
                                <p class="text-muted small mb-1 fw-semibold">Perlu Tindakan</p>
                                <h2 class="fw-bold text-warning mb-0">{{ $pendingCount + $rejectedCount }}</h2>
                                <small class="text-muted">Pending / Revisi</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning fs-3">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SHORTCUT MENU --}}
            <h6 class="fw-bold text-muted text-uppercase mb-3" style="letter-spacing:.08em; font-size:.75rem;">Menu Utama Operator</h6>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <a href="{{ route('operator.datapemain') }}" class="shortcut-card card p-4 text-center">
                        <div class="shortcut-icon" style="background: linear-gradient(135deg, #1a5c2a, #2e7d32);">
                            <i class="bi bi-person-plus-fill text-white"></i>
                        </div>
                        <div class="fw-bold text-dark small">Input Pemain</div>
                        <div class="text-muted" style="font-size:.73rem;">Daftarkan pemain baru</div>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('operator.datapemain') }}" class="shortcut-card card p-4 text-center">
                        <div class="shortcut-icon" style="background: linear-gradient(135deg, #1565c0, #1976d2);">
                            <i class="bi bi-list-check text-white"></i>
                        </div>
                        <div class="fw-bold text-dark small">Lihat Data</div>
                        <div class="text-muted" style="font-size:.73rem;">Kelola data squad</div>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('operator.profile') }}" class="shortcut-card card p-4 text-center">
                        <div class="shortcut-icon" style="background: linear-gradient(135deg, #e65100, #f57c00);">
                            <i class="bi bi-building-fill text-white"></i>
                        </div>
                        <div class="fw-bold text-dark small">Profil Tim</div>
                        <div class="text-muted" style="font-size:.73rem;">Info & logo SSB</div>
                    </a>
                </div>
            </div>

            {{-- STATUS PEMAIN TERBARU --}}
            @if($playersCount > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-activity text-success me-2"></i>Status Pemain Terkini</h6>
                </div>
                <div class="card-body pt-0">
                    @php
                        $allPlayers = \App\Models\Player::where('team_id', $team?->id)->with(['verification'])->latest()->take(5)->get();
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Pemain</th>
                                    <th class="text-center">No. Registrasi</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allPlayers as $p)
                                <tr>
                                    <td class="fw-semibold">{{ $p->name }}</td>
                                    <td class="text-center"><code class="small">{{ $p->registration_number }}</code></td>
                                    <td class="text-center">
                                        @php $s = $p->verification?->status ?? 'pending'; @endphp
                                        @if(in_array($s, ['approved','auto_approved']))
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">✓ Lolos</span>
                                        @elseif($s === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">✗ Revisi</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">⏳ Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($playersCount > 5)
                    <div class="text-center mt-2">
                        <a href="{{ route('operator.datapemain') }}" class="btn btn-sm btn-outline-success">Lihat Semua {{ $playersCount }} Pemain</a>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm p-4 text-center">
                <i class="bi bi-person-plus-fill text-muted fs-1 mb-3"></i>
                <h5 class="fw-bold text-dark">Belum ada pemain terdaftar</h5>
                <p class="text-muted">Mulai daftarkan pemain squad SSB Anda untuk mengikuti turnamen.</p>
                <a href="{{ route('operator.datapemain') }}" class="btn btn-success mx-auto" style="width: fit-content;">
                    <i class="bi bi-person-plus me-1"></i> Input Pemain Pertama
                </a>
            </div>
            @endif

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
