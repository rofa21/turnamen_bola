<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panitia Pusat - Piala Disdikpora Grassroot Kebumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            border: 2px solid #334155;
        }
        .login-header {
            background: #0f172a;
            padding: 28px 20px 22px;
            text-align: center;
            color: #ffffff;
            border-bottom: 3px solid #d4a017;
        }
        .login-header img {
            width: 76px;
            height: 76px;
            object-fit: contain;
            border-radius: 50%;
            border: 3px solid #d4a017;
            background: #ffffff;
            padding: 4px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            margin-bottom: 12px;
        }
        .btn-login {
            background: #2563eb;
            border: none;
            color: white;
            padding: 12px;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-login:hover {
            background: #1d4ed8;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <img src="{{ $activeEvent->logo_url ?? '/images/logo-turnamen.jpg' }}" alt="Logo Panitia Pusat" style="width:72px;height:72px;object-fit:cover;border-radius:50%;border:2px solid var(--emas);margin-bottom:10px;">
        <h5 class="fw-bold text-warning mb-1">PANITIA PUSAT</h5>
        <p class="text-slate-300 small mb-0">{{ $activeEvent->name }}</p>
    </div>
    
    <div class="p-4">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 small" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small text-secondary">Username / Email Administrator</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-shield-lock-fill text-primary"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username admin" required value="{{ old('username') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-secondary">Kata Sandi (Password)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-key-fill text-primary"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> MASUK PANEL ADMIN
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
