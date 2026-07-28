<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator SSB - Piala Disdikpora Grassroot Kebumen</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --hijau-tua: #0f3b1a;
            --hijau: #1a5c2a;
            --emas: #d4a017;
        }
        body {
            background: linear-gradient(135deg, #0f3b1a 0%, #1a5c2a 100%);
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
            box-shadow: 0 15px 35px rgba(0,0,0,0.35);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            border: 2px solid var(--emas);
        }
        .login-header {
            background: linear-gradient(180deg, var(--hijau-tua) 0%, var(--hijau) 100%);
            padding: 28px 20px 22px;
            text-align: center;
            color: #ffffff;
            position: relative;
        }
        .login-header img {
            width: 76px;
            height: 76px;
            object-fit: contain;
            border-radius: 50%;
            border: 3px solid var(--emas);
            background: #ffffff;
            padding: 4px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            margin-bottom: 12px;
        }
        .form-control:focus {
            border-color: var(--hijau);
            box-shadow: 0 0 0 0.25rem rgba(26, 92, 42, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, var(--hijau) 0%, #2e7d32 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-login:hover {
            filter: brightness(1.1);
            color: white;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <img src="/images/logo-turnamen.jpg" alt="Logo Piala Disdikpora">
        <h5 class="fw-bold text-warning mb-1">PIALA DISDIKPORA KEBUMEN</h5>
        <p class="text-light small mb-0">Portal Login Operator Sekolah Sepak Bola (SSB)</p>
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

        <form action="{{ route('operator.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small text-secondary">Username / ID Tim</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person-fill text-success"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username operator" required value="{{ old('username') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-secondary">Kata Sandi (Password)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-success"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> MASUK DASBOR OPERATOR
            </button>
        </form>

        <div class="text-center mt-4 border-top pt-3">
            <small class="text-muted">Kendala akun? Hubungi Panitia Pusat Disdikpora Kebumen.</small>
        </div>
    </div>
</div>

<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>