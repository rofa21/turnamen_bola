<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Resmi - Piala Disdikpora Grassroot Regional Kebumen</title>
    <meta name="description" content="Portal Resmi Turnamen Sepak Bola Usia Dini Piala Disdikpora Grassroot Regional Kebumen. Informasi pendaftaran, jadwal pertandingan, dan akses sistem.">
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --hijau-tua: #0f3b1a;
            --hijau: #1a5c2a;
            --hijau-muda: #2e7d32;
            --emas: #d4a017;
            --emas-muda: #f5cf66;
            --gelap: #121814;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f4f7f4;
            color: #2b302c;
        }

        /* NAVBAR PORTAL */
        .portal-nav {
            background: linear-gradient(135deg, var(--hijau-tua) 0%, var(--hijau) 100%);
            border-bottom: 3px solid var(--emas);
        }
        .portal-brand img {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border: 2px solid var(--emas);
            border-radius: 50%;
        }

        /* HERO HEADER BANNER */
        .hero-banner-container {
            position: relative;
            background-color: var(--hijau-tua);
            overflow: hidden;
            border-bottom: 4px solid var(--emas);
        }
        .hero-banner-img {
            width: 100%;
            max-height: 380px;
            object-fit: cover;
            opacity: 0.85;
            filter: brightness(0.9);
        }
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(15, 59, 26, 0.4) 0%, rgba(15, 59, 26, 0.95) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        /* PORTAL LOGIN CARDS */
        .portal-card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            overflow: hidden;
            position: relative;
        }
        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(26, 92, 42, 0.18);
        }
        .portal-card-op {
            border-top: 5px solid var(--hijau-muda);
        }
        .portal-card-admin {
            border-top: 5px solid #0d6efd;
        }
        .portal-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
            font-size: 2.2rem;
        }

        /* STAT CARDS */
        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            border-left: 4px solid var(--emas);
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }

        /* FOOTER */
        .portal-footer {
            background-color: var(--hijau-tua);
            color: #d1e7dd;
            border-top: 3px solid var(--emas);
        }
    </style>
</head>
<body>

    <!-- NAVBAR PORTAL -->
    <nav class="navbar navbar-expand-lg navbar-dark portal-nav sticky-top shadow-sm py-2" id="main-nav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center portal-brand" href="{{ route('portal') }}">
                <img src="/images/logo-turnamen.jpg" alt="Logo Disdikpora" class="me-2 shadow-sm">
                <div>
                    <div class="fw-bold text-warning" style="font-size: 1.05rem; line-height: 1.1;">DISDIKPORA KEBUMEN</div>
                    <small class="text-light opacity-75" style="font-size: 0.72rem;">Portal Resmi Turnamen Sepak Bola Grassroot</small>
                </div>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('operator.login') }}" class="btn btn-outline-warning btn-sm fw-bold px-3 me-1">
                    <i class="bi bi-person-circle me-1"></i> Login SSB
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-warning btn-sm fw-bold px-3 shadow-sm">
                    <i class="bi bi-shield-lock-fill me-1"></i> Login Panitia
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO HEADER BANNER EVENT -->
    <section class="hero-banner-container" id="hero-section">
        <img src="/images/banner-turnamen.jpg" alt="Banner Header Disdikpora Grassroot Kebumen" class="hero-banner-img">
        <div class="hero-overlay">
            <div class="container text-white py-4">
                <span class="badge px-3 py-2 mb-3 shadow-sm fw-bold text-dark" style="background-color: var(--emas); font-size: 0.85rem;">
                    <i class="bi bi-trophy-fill me-1"></i> REGIONAL KABUPATEN KEBUMEN
                </span>
                <h1 class="display-5 fw-extrabold text-uppercase text-white mb-2" style="letter-spacing: -0.02em; text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                    PIALA DISDIKPORA GRASSROOT KEBUMEN
                </h1>
                <p class="lead text-light opacity-90 mx-auto mb-4" style="max-width: 750px; font-weight: 400; font-size: 1.15rem;">
                    Sistem Informasi & Portal Resmi Kejuaraan Sepak Bola Usia Dini Dinas Pendidikan, Kepemudaan, dan Olahraga Kabupaten Kebumen
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#akses-portal" class="btn btn-warning btn-lg fw-bold px-4 py-2 shadow" style="background-color: var(--emas); border: none; color: #000;">
                        <i class="bi bi-arrow-down-circle-fill me-2"></i> Akses Portal Sistem
                    </a>
                    <a href="#info-turnamen" class="btn btn-outline-light btn-lg fw-semibold px-4 py-2">
                        <i class="bi bi-info-circle me-2"></i> Informasi Event
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS STRIP -->
    <section class="py-4 bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-card text-start h-100">
                        <div class="small text-muted fw-bold">TOTAL SSB PESERTA</div>
                        <div class="fs-2 fw-bold text-success mb-0">{{ $totalTeams }} SSB</div>
                        <small class="text-muted">Se-Kabupaten Kebumen</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card text-start h-100" style="border-left-color: #0d6efd;">
                        <div class="small text-muted fw-bold">PEMAIN TERKUMPUL</div>
                        <div class="fs-2 fw-bold text-primary mb-0">{{ $totalPlayers }} Pemain</div>
                        <small class="text-muted">Terdaftar di Sistem</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card text-start h-100" style="border-left-color: #198754;">
                        <div class="small text-muted fw-bold">LOLOS VERIFIKASI</div>
                        <div class="fs-2 fw-bold text-success mb-0">{{ $approvedPlayers }} Pemain</div>
                        <small class="text-muted">Berkas Disetujui Panitia</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card text-start h-100" style="border-left-color: var(--emas);">
                        <div class="small text-muted fw-bold">KATEGORI UMUR</div>
                        <div class="fs-2 fw-bold text-dark mb-0">KU-10 & KU-12</div>
                        <small class="text-muted">Kelahiran 2016 & 2014</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PORTAL AKSES UTAMA (REQUIREMENT 5) -->
    <section class="py-5" id="akses-portal">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 mb-2" style="font-size: .85rem;">
                    PORTAL LOGIN RESMI
                </span>
                <h2 class="fw-extrabold text-dark display-6 mb-2">Pilih Pintu Masuk Portal</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Silakan pilih akses portal sesuai dengan peranan Anda dalam ajang Piala Disdikpora Grassroot Kebumen.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- CARD PORTAL OPERATOR SSB -->
                <div class="col-md-5">
                    <div class="card portal-card portal-card-op p-4 text-center h-100">
                        <div class="portal-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Portal Operator SSB</h4>
                        <p class="text-muted small mb-4">
                            Khusus Pengurus / Operator Sekolah Sepak Bola (SSB) peserta turnamen se-Kabupaten Kebumen untuk pendaftaran squad pemain & berkas.
                        </p>
                        <ul class="list-unstyled text-start small text-muted mb-4 mx-auto" style="max-width: 320px;">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Input & kelola data squad pemain SSB</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Upload berkas (Foto, Akta, KK, KIA/Ijazah)</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Pantau status verifikasi berkas pemain</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="{{ route('operator.login') }}" class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Portal Operator SSB
                            </a>
                        </div>
                    </div>
                </div>

                <!-- CARD PORTAL PANITIA PUSAT ADMIN -->
                <div class="col-md-5">
                    <div class="card portal-card portal-card-admin p-4 text-center h-100">
                        <div class="portal-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Portal Panitia Pusat</h4>
                        <p class="text-muted small mb-4">
                            Khusus Tim Penyelenggara & Verifikator Dinas Pendidikan, Kepemudaan, dan Olahraga Kabupaten Kebumen.
                        </p>
                        <ul class="list-unstyled text-start small text-muted mb-4 mx-auto" style="max-width: 320px;">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Verifikasi berkas & kelayakan umur pemain</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Pengaturan jadwal pertandingan & lapangan</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Cetak Manifes Buku Tim & ID Card Lolos</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-shield-lock me-2"></i> Masuk Portal Panitia Pusat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFORMASI DISDIKPORA KEBUMEN & EVENT (REQUIREMENT 5) -->
    <section class="py-5 bg-white border-top border-bottom" id="info-turnamen">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-2" style="background-color: var(--emas) !important;">
                        TENTANG EVENT DISDIKPORA KEBUMEN
                    </span>
                    <h2 class="fw-bold text-dark mb-3">Membangun Generasi Emas Sepak Bola Kebumen</h2>
                    <p class="text-muted" style="line-height: 1.7;">
                        <strong>Dinas Pendidikan, Kepemudaan, dan Olahraga (Disdikpora) Kabupaten Kebumen</strong> berkomitmen secara berkelanjutan untuk membina potensi atlet muda usia dini melalui ajang <em>Piala Disdikpora Grassroot Kebumen</em>.
                    </p>
                    <p class="text-muted" style="line-height: 1.7;">
                        Kejuaraan ini diikuti oleh Sekolah Sepak Bola (SSB) terdaftar dari 26 kecamatan di wilayah Kabupaten Kebumen, menjunjung tinggi nilai sportivitas, fair play, dan tertib administrasi sejak dini.
                    </p>

                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded border">
                                <i class="bi bi-geo-alt-fill text-danger fs-4 d-block mb-1"></i>
                                <strong class="d-block text-dark small">Lokasi Pertandingan</strong>
                                <span class="text-muted small">Stadion Chandradimuka, Kebumen</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded border">
                                <i class="bi bi-calendar-event-fill text-success fs-4 d-block mb-1"></i>
                                <strong class="d-block text-dark small">Musim Kompetisi</strong>
                                <span class="text-muted small">Tahun Ajaran 2026 / 2027</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 bg-light rounded-4 border-start border-success border-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-trophy text-warning me-2"></i> Ketentuan Kategori Usia</h5>
                        
                        <div class="mb-3 p-3 bg-white rounded border">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-success mb-1">Kategori KU-10</h6>
                                    <small class="text-muted">Maksimal Kelahiran Tahun 2016 / 2017</small>
                                </div>
                                <span class="badge bg-success">Kelompok Usia 10 Tahun</span>
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-white rounded border">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">Kategori KU-12</h6>
                                    <small class="text-muted">Maksimal Kelahiran Tahun 2014 / 2015</small>
                                </div>
                                <span class="badge bg-primary">Kelompok Usia 12 Tahun</span>
                            </div>
                        </div>

                        <div class="alert alert-success mb-0 border-0 shadow-sm small">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Verifikasi berkas pemain dilakukan secara ketat melalui pencocokan NIK, Akta Kelahiran, KK, serta dokumen pendukung resmi.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RINGKASAN JADWAL PERTANDINGAN -->
    @if($schedules->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 mb-1">JADWAL RESMI</span>
                    <h3 class="fw-bold text-dark mb-0">Jadwal Pertandingan Terbaru</h3>
                </div>
            </div>

            <div class="row g-3">
                @foreach($schedules as $sch)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold">{{ $sch->ageCategory?->name ?? 'KU' }}</span>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $sch->match_time }} WIB</small>
                        </div>
                        <div class="text-center py-2 bg-light rounded my-2">
                            <div class="fw-bold text-dark">{{ $sch->homeTeam?->name ?? 'Tim A' }}</div>
                            <small class="text-muted fw-bold">VS</small>
                            <div class="fw-bold text-dark">{{ $sch->awayTeam?->name ?? 'Tim B' }}</div>
                        </div>
                        <small class="text-muted d-block text-center mt-1">
                            <i class="bi bi-geo-alt me-1"></i>{{ $sch->location ?? 'Stadion Chandradimuka' }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- FOOTER RESMI DISDIKPORA KEBUMEN -->
    <footer class="portal-footer py-4">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <img src="/images/logo-turnamen.jpg" alt="Logo Turnamen" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;border:1px solid var(--emas);">
                <span class="fw-bold text-warning" style="letter-spacing: .05em;">DISDIKPORA KABUPATEN KEBUMEN</span>
            </div>
            <p class="small text-light opacity-75 mb-2">
                Dinas Pendidikan, Kepemudaan, dan Olahraga Kabupaten Kebumen • Jawa Tengah
            </p>
            <small class="text-muted" style="font-size: 0.72rem;">
                © {{ date('Y') }} Piala Disdikpora Grassroot Regional Kebumen. All Rights Reserved.
            </small>
        </div>
    </footer>

    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
