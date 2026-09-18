{{--
==============================================================================
Tujuan: Halaman formulir login untuk Administrator dan Guru.
Dipakai Oleh: AuthController@showLoginForm (Route /login)
Dependensi: Blade Engine, CSRF Token
Fungsi Utama: Input email & password, remember me, pengiriman credential POST /login
Side Effect: Menampilkan pesan error validasi atau alert logout
==============================================================================
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Guru</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .card-header {
            background: #f8fafc;
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .brand-icon {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.8rem;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .card-header h1 {
            font-size: 1.35rem;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .card-header p {
            font-size: 0.85rem;
            color: #64748b;
        }

        .card-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.15s ease;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 0.85rem;
            color: #475569;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .demo-accounts {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px dashed #e2e8f0;
            font-size: 0.78rem;
            color: #64748b;
        }

        .demo-accounts strong {
            display: block;
            color: #334155;
            margin-bottom: 6px;
        }

        .demo-badge {
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card-header">
            <div class="brand-icon">🎓</div>
            <h1>Portal Guru</h1>
            <p>Sistem Informasi Akademik, Presensi & Penilaian</p>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@sekolah.sch.id">
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <div class="form-options">
                    <label class="form-checkbox">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    Masuk ke Portal
                </button>
            </form>

            <div class="demo-accounts">
                <strong>Akun Uji Coba Default:</strong>
                <div>Admin: <span class="demo-badge">admin@portalguru.test</span> / <span class="demo-badge">password</span></div>
                <div style="margin-top: 3px;">Guru: <span class="demo-badge">guru@portalguru.test</span> / <span class="demo-badge">password</span></div>
            </div>
        </div>
    </div>
</body>
</html>
