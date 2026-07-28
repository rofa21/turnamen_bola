<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Dokumen Tim - Operator SSB</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --hijau: #1a5c2a; --hijau-tua: #0f3b1a; --emas: #d4a017; }
        body { background-color: #f0f4f0; font-family: 'Segoe UI', sans-serif; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, var(--hijau-tua) 0%, var(--hijau) 100%); }
        .sidebar-brand { background: rgba(0,0,0,0.3); border-bottom: 2px solid var(--emas); }
        .sidebar .nav-link { color: #c8e6c9; margin-bottom: 3px; border-radius: 8px; transition: all 0.2s; padding: 8px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: linear-gradient(135deg, var(--emas) 0%, #b8870f 100%); }

        .player-card {
            width: 100%;
            max-width: 320px;
            height: 190px;
            border: 2px solid #1a5c2a;
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff 0%, #f1f4f9 100%);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }
        .player-card-header {
            background-color: #1a5c2a;
            color: white;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        @media print {
            body * { visibility: hidden; }
            .printable-area, .printable-area * { visibility: visible; }
            .printable-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR OPERATOR SSB -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0 no-print">
            <div class="sidebar-brand text-center p-3">
                <img src="{{ $team?->logo_url ?? '/images/logo-turnamen.jpg' }}" alt="Logo Tim" class="rounded-circle border border-warning border-2 mb-2" style="width:48px;height:48px;object-fit:cover;">
                <div class="fw-bold text-warning" style="font-size:.85rem;">{{ $operator->name }}</div>
                <small class="text-light" style="font-size:.72rem;">Operator SSB</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.datapemain') }}"><i class="bi bi-people-fill me-2"></i> Data Pemain</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('operator.profile') }}"><i class="bi bi-building me-2"></i> Profil Tim SSB</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('operator.print.index') }}"><i class="bi bi-printer-fill me-2"></i> Cetak Manifes & ID Card</a></li>
                <hr class="border-slate-700 my-3">
                <li class="nav-item">
                    <form action="{{ route('operator.logout') }}" method="POST">
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
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom no-print">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-printer-fill text-primary me-2"></i> Cetak Dokumen Tim SSB</h2>
                    <p class="text-muted mb-0">Cetak Manifes Squad Resmi dan Kartu Identitas Pemain.</p>
                </div>
            </div>

            <!-- PANEL KONTROL -->
            <form action="{{ route('operator.print.index') }}" method="GET" class="card border-0 shadow-sm p-4 mb-4 no-print">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Pilih Format Dokumen</label>
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="buku-tim" {{ $documentType == 'buku-tim' ? 'selected' : '' }}>Buku Tim / Manifes Squad Resmi</option>
                            <option value="kartu-pemain" {{ $documentType == 'kartu-pemain' ? 'selected' : '' }}>Kartu Identitas Pemain (ID Card)</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100 fw-bold shadow-sm" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i> Cetak Dokumen Sekarang
                        </button>
                    </div>
                </div>
            </form>

            <!-- AREA CETAK -->
            <div class="card border-0 shadow-sm p-5 printable-area bg-white">
                @if($team)
                    @if($documentType == 'buku-tim')
                        <!-- BUKU TIM / MANIFES -->
                        <div id="previewBukuTim">
                            <div class="text-center border-bottom pb-3 mb-4">
                                <h5 class="fw-bold text-uppercase mb-1">{{ $event->organizer ?? 'Dinas Pendidikan, Kepemudaan, dan Olahraga' }}</h5>
                                <h4 class="fw-bold text-uppercase text-primary mb-1">{{ $event->name ?? 'Piala Disdikpora Grassroot Regional Kebumen 2026' }}</h4>
                                <small class="text-muted">{{ $event->location ?? 'Stadion Chandradimuka Kebumen' }} • Manifes Resmi SSB</small>
                            </div>

                            <div class="row mb-4 align-items-center bg-light p-3 rounded">
                                <div class="col-md-8">
                                    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-shield-fill-check text-primary me-2"></i> {{ strtoupper($team->name) }}</h4>
                                    <p class="text-muted mb-0 small">Kategori: <strong>{{ $team->ageCategory?->name }}</strong> | Kecamatan: <strong>{{ $team->district ?? 'Kebumen' }}</strong></p>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <span class="badge bg-success p-2">Total Pemain: {{ $team->players->count() }} Orang</span>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-text me-1"></i> Susunan Pemain SSB {{ $team->name }}</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-4">
                                    <thead class="table-dark text-center">
                                        <tr style="font-size: 0.9rem;">
                                            <th width="5%">No</th>
                                            <th width="20%">No. Registrasi</th>
                                            <th width="25%">Nama Lengkap Pemain</th>
                                            <th width="10%">No. Punggung</th>
                                            <th width="15%">Posisi</th>
                                            <th width="15%">Tahun Lahir</th>
                                            <th width="10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($team->players as $pIndex => $p)
                                            <tr>
                                                <td class="text-center">{{ $pIndex + 1 }}</td>
                                                <td><code>{{ $p->registration_number ?? '-' }}</code></td>
                                                <td class="fw-bold">{{ $p->name }}</td>
                                                <td class="text-center">{{ $p->jersey_number ?? '-' }}</td>
                                                <td class="text-center">{{ $p->position ?? 'Pemain' }}</td>
                                                <td class="text-center">{{ $p->birth_year }}</td>
                                                <td class="text-center">{!! $p->verification?->status_badge ?? 'Pending' !!}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-center py-3">Belum ada pemain diinput.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-4 text-center">
                                    <p class="mb-5">Manajer Tim,<br><br><strong>{{ $team->manager_name ?? 'Manajer SSB' }}</strong></p>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4 text-center">
                                    <p class="mb-5">Mengetahui,<br>Ketua Panitia Pusat Disdikpora<br><br><strong>Drs. H. Slamet, M.Pd</strong></p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- KARTU PEMAIN -->
                        <div id="previewKartuPemain">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-dark">ID Card Pemain SSB {{ $team->name }}</h4>
                                <p class="text-muted small">Cetak ID Card resmi turnamen.</p>
                            </div>
                            <div class="row g-4 justify-content-center">
                                @forelse($team->players as $p)
                                    <div class="col-md-6 mb-3">
                                        <div class="player-card">
                                            <div class="player-card-header d-flex justify-content-between align-items-center">
                                                <span>ID CARD PEMAIN RESMI</span>
                                                <span>{{ $team->ageCategory?->name }}</span>
                                            </div>
                                            <div class="p-3 d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-25 rounded me-3 d-flex align-items-center justify-content-center overflow-hidden" style="width: 70px; height: 90px;">
                                                    @if($p->foto_url)
                                                        <img src="{{ $p->foto_url }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="bi bi-person-fill fs-2 text-secondary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1">{{ $p->name }}</h6>
                                                    <small class="text-muted d-block">No. Punggung: <strong>{{ $p->jersey_number }}</strong></small>
                                                    <small class="text-muted d-block">SSB: {{ $team->name }}</small>
                                                    <small class="text-muted d-block">No. Reg: <code>{{ $p->registration_number }}</code></small>
                                                    <span class="badge bg-success mt-1" style="font-size: 0.65rem;">Peserta Resmi Turnamen</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted">Belum ada pemain untuk dicetak kartu.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">Belum ada data tim. Harap lengkapi profil tim Anda terlebih dahulu.</div>
                @endif
            </div>

        </main>
    </div>
</div>

<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
