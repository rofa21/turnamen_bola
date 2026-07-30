<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekspor Data - Panitia Pusat</title>
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.export.index') }}"><i class="bi bi-file-earmark-excel-fill me-2"></i> Ekspor Data</a></li>
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
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h2 class="h3 fw-bold text-dark"><i class="bi bi-file-earmark-excel-fill text-success me-2"></i> Ekspor Data Sistem</h2>
                    <p class="text-muted mb-0">Unduh data pemain, data tim, dan status verifikasi dalam format Excel / CSV rapi.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 h-100 border-start border-success border-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded text-success me-3 fs-3">
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Ekspor Data Pemain (Excel Rapi)</h5>
                                <small class="text-muted">Format spreadsheet terpisah kolom rapi dengan header warna.</small>
                            </div>
                        </div>
                        <form action="{{ route('admin.export.excel') }}" method="GET" class="mt-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Kategori Usia</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori (KU-10 & KU-12)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Filter Tim SSB</label>
                                <select name="team_id" class="form-select">
                                    <option value="">Semua SSB Peserta</option>
                                    @foreach($teams as $tm)
                                        <option value="{{ $tm->id }}">{{ $tm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Format File Download</label>
                                <select name="format" class="form-select fw-bold text-success">
                                    <option value="xls" selected>Microsoft Excel (.xls) - Kolom Rapi & Terpisah (Rekomendasi)</option>
                                    <option value="csv">CSV Semicolon (.csv)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                                <i class="bi bi-download me-2"></i> Download File Excel Rapi (.xls)
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 h-100 border-start border-primary border-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded text-primary me-3 fs-3">
                                <i class="bi bi-printer-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Cetak / Export PDF Buku Tim</h5>
                                <small class="text-muted">Cetak laporan resmi susunan tim terverifikasi.</small>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-muted small">Fitur cetak buku tim dan ID Card pemain disesuaikan dengan layout resmi panitia pusat Disdikpora.</p>
                            <a href="{{ route('admin.print.index') }}" class="btn btn-primary w-100 fw-bold shadow-sm">
                                <i class="bi bi-arrow-right-circle me-2"></i> Buka Pusat Cetak Dokumen PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
