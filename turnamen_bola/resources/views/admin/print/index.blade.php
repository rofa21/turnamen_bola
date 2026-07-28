<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Cetak Dokumen - Panitia Pusat</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Arial, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; z-index: 100; }
        .sidebar .nav-link { color: #adb5bd; margin-bottom: 5px; border-radius: 5px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
        
        .player-card {
            width: 100%;
            max-width: 320px;
            height: 200px;
            border: 2px solid #1a5c2a;
            border-radius: 10px;
            background: linear-gradient(135deg, #ffffff 0%, #f4f9f5 100%);
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            margin: 0 auto;
        }
        .player-card-header {
            background-color: #1a5c2a;
            color: #d4a017;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: bold;
            letter-spacing: 0.05em;
        }

        .player-photo-print {
            width: 42px;
            height: 52px;
            object-fit: cover;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        @media print {
            body * { visibility: hidden; }
            .printable-area, .printable-area * { visibility: visible; }
            .printable-area { position: absolute; left: 0; top: 0; width: 100%; padding: 0 !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR SUPER ADMIN -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3 no-print">
            <div class="text-center py-3 border-bottom border-secondary mb-3">
                <img src="/images/logo-turnamen.jpg" alt="Logo Panitia" class="rounded-circle border border-warning border-2 mb-2" style="width:48px;height:48px;object-fit:cover;">
                <h6 class="text-white fw-bold mt-2 mb-0">PANITIA PUSAT</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Disdikpora Grassroot Kebumen</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.operators.index') }}"><i class="bi bi-people-fill me-2"></i> Akun Operator</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.teams.index') }}"><i class="bi bi-shield-shaded me-2"></i> Data Tim & Verifikasi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedule.index') }}"><i class="bi bi-calendar-check me-2"></i> Jadwal Pertandingan</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.print.index') }}"><i class="bi bi-printer-fill me-2"></i> Pusat Cetak Dokumen</a></li>
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
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom no-print">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-printer-fill text-primary me-2"></i> Pusat Cetak Dokumen & Manifes Pemain</h2>
                    <p class="text-muted mb-0">Cetak Manifes Buku Tim & ID Card Pemain. <strong>Hanya pemain yang LOLOS VERIFIKASI yang dicetak.</strong></p>
                </div>
            </div>

            <!-- PANEL KONTROL & FILTER -->
            <form action="{{ route('admin.print.index') }}" method="GET" class="card border-0 shadow-sm p-4 mb-4 no-print">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-sliders text-primary me-2"></i> Filter & Pengaturan Cetak</h5>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Filter Otomatis: Hanya Pemain Lolos</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Pilih Sekolah Sepak Bola (SSB)</label>
                        <select name="team_id" class="form-select" onchange="this.form.submit()">
                            @foreach($teams as $t)
                                <option value="{{ $t->id }}" {{ $selectedTeamId == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->ageCategory?->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Jenis Dokumen Cetak</label>
                        <select name="type" class="form-select" id="jenisDokumen" onchange="this.form.submit()">
                            <option value="buku-tim" {{ $documentType == 'buku-tim' ? 'selected' : '' }}>Buku Tim / Manifes Squad Resmi (Dengan Foto Pemain)</option>
                            <option value="kartu-pemain" {{ $documentType == 'kartu-pemain' ? 'selected' : '' }}>Kartu Identitas Pemain (ID Card)</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100 fw-bold shadow-sm" onclick="window.print()">
                            <i class="bi bi-printer-fill me-2"></i> Cetak / Print Dokumen Ini
                        </button>
                    </div>
                </div>

                <hr class="my-3">

                <!-- PENGATURAN TANDA TANGAN / PARAF (OPSIONAL) -->
                <div class="accordion" id="accordionSignature">
                    <div class="accordion-item border-0 bg-light rounded">
                        <h2 class="accordion-header" id="headingSig">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSig">
                                <i class="bi bi-pen me-2 text-primary"></i> Pengaturan Tanda Tangan / Paraf Cetak (Opsional)
                            </button>
                        </h2>
                        <div id="collapseSig" class="accordion-collapse collapse" data-bs-parent="#accordionSignature">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Kota & Tanggal Cetak</label>
                                        <input type="text" name="sign_location" class="form-control form-control-sm" value="{{ request('sign_location', $signature['location']) }}" placeholder="Kebumen">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Tanggal TTD</label>
                                        <input type="text" name="sign_date" class="form-control form-control-sm" value="{{ request('sign_date', $signature['date']) }}" placeholder="28 Juli 2026">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Penandatangan Kiri (SSB)</label>
                                        <input type="text" name="sign_name_left" class="form-control form-control-sm" value="{{ request('sign_name_left', $signature['name_left']) }}" placeholder="Nama Manajer SSB">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Penandatangan Kanan (Panitia)</label>
                                        <input type="text" name="sign_name_right" class="form-control form-control-sm" value="{{ request('sign_name_right', $signature['name_right']) }}" placeholder="Drs. H. Slamet, M.Pd">
                                    </div>
                                </div>
                                <div class="mt-2 text-end">
                                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-check2 me-1"></i> Terapkan Tanda Tangan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- AREA CETAK -->
            <div class="card border-0 shadow-sm p-4 p-md-5 printable-area bg-white">
                @if($team)
                    @if($documentType == 'buku-tim')
                        <!-- 1. BUKU TIM / MANIFES SQUAD RESMI -->
                        <div id="previewBukuTim">
                            <!-- KOP SURAT / HEADER DOKUMEN -->
                            <div class="d-flex align-items-center border-bottom border-dark border-3 pb-3 mb-4">
                                <img src="/images/logo-turnamen.jpg" alt="Logo Turnamen" class="me-3" style="width:75px;height:75px;object-fit:contain;">
                                <div class="flex-grow-1 text-center">
                                    <h5 class="fw-bold text-uppercase mb-0" style="letter-spacing:.05em; color:#1a5c2a;">DINAS PENDIDIKAN, KEPEMUDAAN, DAN OLAHRAGA</h5>
                                    <h4 class="fw-bold text-uppercase text-dark mb-0" style="letter-spacing:.03em;">PANITIA PUSAT PIALA DISDIKPORA GRASSROOT KEBUMEN</h4>
                                    <small class="text-muted">Sekretariat: Stadion Chandradimuka, Kab. Kebumen • Jawa Tengah</small>
                                </div>
                                <img src="/images/logo-turnamen.jpg" alt="Logo Kebumen" class="ms-3 opacity-0" style="width:75px;height:75px;">
                            </div>

                            <div class="row mb-4 align-items-center bg-light p-3 rounded border">
                                <div class="col-8">
                                    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-shield-fill-check text-success me-2"></i> {{ strtoupper($team->name) }}</h4>
                                    <p class="text-muted mb-0 small">Kategori Usia: <strong>{{ $team->ageCategory?->name }}</strong> | Kecamatan: <strong>{{ $team->district ?? 'Kebumen' }}</strong> | Manajer: <strong>{{ $team->manager_name ?? '-' }}</strong></p>
                                </div>
                                <div class="col-4 text-end">
                                    <span class="badge bg-success p-2 fs-6">SQUAD RESMI LOLOS VERIFIKASI ({{ $team->players->count() }} Pemain)</span>
                                </div>
                            </div>

                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-text-fill me-1"></i> MANIFES RESMI ANGGOTA SQUAD DENGAN FOTO PEMAIN</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-4">
                                    <thead class="table-dark text-center">
                                        <tr style="font-size: 0.88rem;">
                                            <th width="4%">No</th>
                                            <th width="8%">Pas Foto</th>
                                            <th width="16%">No. Registrasi</th>
                                            <th width="24%">Nama Lengkap Pemain</th>
                                            <th width="15%">NIK & Tgl Lahir</th>
                                            <th width="10%">No. Punggung</th>
                                            <th width="11%">Posisi</th>
                                            <th width="12%">Paraf/Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($team->players as $pIndex => $p)
                                            @php
                                                $pdocs = $p->documents->keyBy('type');
                                                $fotoDoc = $pdocs->get('foto');
                                                $fotoUrl = $fotoDoc ? asset('storage/'.$fotoDoc->file_path) : null;
                                            @endphp
                                            <tr>
                                                <td class="text-center small">{{ $pIndex + 1 }}</td>
                                                <td class="text-center">
                                                    @if($fotoUrl)
                                                        <img src="{{ $fotoUrl }}" alt="Foto {{ $p->name }}" class="player-photo-print">
                                                    @else
                                                        <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center mx-auto" style="width:40px;height:50px;">
                                                            <i class="bi bi-person text-secondary"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center"><code>{{ $p->registration_number ?? '-' }}</code></td>
                                                <td>
                                                    <span class="fw-bold text-dark d-block">{{ $p->name }}</span>
                                                    <small class="text-muted">Kelahiran {{ $p->birth_year }}</small>
                                                </td>
                                                <td>
                                                    <small class="d-block text-muted">{{ $p->nik ?? '-' }}</small>
                                                    <small class="d-block text-dark">{{ $p->birth_date?->format('d/m/Y') ?? '-' }}</small>
                                                </td>
                                                <td class="text-center fw-bold text-primary">
                                                    {{ $p->jersey_number ? '#'.$p->jersey_number : '-' }}
                                                </td>
                                                <td class="text-center"><span class="badge bg-light text-dark border">{{ $p->position ?? 'Pemain' }}</span></td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle small fw-bold">✓ LOLOS</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada pemain yang lolos verifikasi untuk tim ini.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- LEMBAR SIGNATURE / PARAF TANDA TANGAN (REQUIREMENT 3) -->
                            <div class="row mt-5 pt-3 align-items-end">
                                <div class="col-6 text-center">
                                    <p class="mb-1 text-muted small">Kebumen, {{ $signature['date'] }}</p>
                                    <p class="fw-bold mb-5">{{ $signature['title_left'] }}</p>
                                    <br><br>
                                    <p class="fw-bold text-decoration-underline mb-0">{{ $signature['name_left'] }}</p>
                                    <small class="text-muted">Tanda Tangan & Stempel SSB</small>
                                </div>
                                <div class="col-6 text-center">
                                    <p class="mb-1 text-muted small">Mengetahui & Menyetujui,</p>
                                    <p class="fw-bold mb-5">{{ $signature['title_right'] }}</p>
                                    <br><br>
                                    <p class="fw-bold text-decoration-underline mb-0">{{ $signature['name_right'] }}</p>
                                    <small class="text-muted">NIP. 19740512 199803 1 004</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- 2. KARTU PEMAIN (ID CARD RESMI) -->
                        <div id="previewKartuPemain">
                            <div class="text-center mb-4 border-bottom pb-2">
                                <h4 class="fw-bold text-dark">ID Card Pemain Resmi Lolos Verifikasi</h4>
                                <p class="text-muted small">Hanya mencetak pemain yang telah diverifikasi Panitia Pusat • {{ $team->name }} ({{ $team->ageCategory?->name }})</p>
                            </div>
                            <div class="row g-4 justify-content-center">
                                @forelse($team->players as $p)
                                    @php
                                        $pdocs = $p->documents->keyBy('type');
                                        $fotoDoc = $pdocs->get('foto');
                                        $fotoUrl = $fotoDoc ? asset('storage/'.$fotoDoc->file_path) : null;
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="player-card">
                                            <div class="player-card-header d-flex justify-content-between align-items-center">
                                                <span>PIALA DISDIKPORA KEBUMEN</span>
                                                <span>{{ $team->ageCategory?->name }}</span>
                                            </div>
                                            <div class="p-3 d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-25 rounded me-3 d-flex align-items-center justify-content-center overflow-hidden" style="width: 75px; height: 95px; border:1px solid #ced4da;">
                                                    @if($fotoUrl)
                                                        <img src="{{ $fotoUrl }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="bi bi-person-fill fs-1 text-secondary"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-dark mb-1">{{ $p->name }}</h6>
                                                    <small class="text-muted d-block">No. Punggung: <strong>{{ $p->jersey_number ? '#'.$p->jersey_number : '-' }}</strong></small>
                                                    <small class="text-muted d-block">SSB: <strong>{{ $team->name }}</strong></small>
                                                    <small class="text-muted d-block">Reg: <code>{{ $p->registration_number }}</code></small>
                                                    <span class="badge bg-success mt-1" style="font-size: 0.65rem;">✓ LOLOS VERIFIKASI</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5 text-muted">Belum ada pemain yang lolos verifikasi untuk dicetak ID Card.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">Silakan pilih SSB peserta untuk mencetak dokumen.</div>
                @endif
            </div>

        </main>
    </div>
</div>

<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
