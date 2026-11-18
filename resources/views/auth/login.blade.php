<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#0b6657">
  <title>Login e-Rapor Sekolah</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --green: #0b6657;
      --deep: #084b43;
      --gold: #dfbd70
    }

    * {
      box-sizing: border-box
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #21483e;
      background: linear-gradient(135deg, #e9f4ed 0%, #f7faf7 45%, #e5f1eb 100%);
      overflow-x: hidden
    }

    .login-wrap {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 28px;
      position: relative;
      isolation: isolate
    }

    .login-wrap:before,
    .login-wrap:after {
      content: "";
      position: fixed;
      border-radius: 50%;
      z-index: -1;
      filter: blur(1px);
      animation: drift 10s ease-in-out infinite
    }

    .login-wrap:before {
      width: 460px;
      height: 460px;
      top: -180px;
      right: -100px;
      background: radial-gradient(circle, #b7dfc9a8, transparent 68%)
    }

    .login-wrap:after {
      width: 500px;
      height: 500px;
      bottom: -230px;
      left: -130px;
      background: radial-gradient(circle, #e7d39a88, transparent 68%);
      animation-delay: -4s
    }

    @keyframes drift {

      0%,
      100% {
        transform: translate(0, 0)
      }

      50% {
        transform: translate(22px, -20px)
      }
    }

    .login-card {
      width: min(465px, 100%);
      border: 1px solid #ffffffd9;
      border-radius: 28px;
      background: #ffffffd9;
      backdrop-filter: blur(18px);
      box-shadow: 0 25px 80px #174b3b1a;
      padding: clamp(28px, 5vw, 48px);
      position: relative;
      overflow: hidden
    }

    .login-card:before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #0b6657, #dfbd70, #0b6657)
    }

    .login-title {
      font-size: 25px;
      font-weight: 800;
      letter-spacing: -.04em;
      color: #124d42
    }

    .login-subtitle {
      color: #789087;
      font-size: 13px
    }

    .form-label {
      font-weight: 700;
      font-size: 13px;
      color: #34594e
    }

    .form-control {
      border: 1px solid #dce9e1;
      border-radius: 13px;
      padding: 13px 15px;
      background: #fbfdfb
    }

    .form-control:focus {
      border-color: #61a58e;
      box-shadow: 0 0 0 .22rem #0b66571a;
      background: white
    }

    .input-group-text {
      border: 1px solid #dce9e1;
      border-radius: 13px 13px 13px 13px;
      background: #f0f7f2;
      color: #528173
    }

    .input-group .form-control {
      border-radius: 0 13px 13px 0
    }

    .btn-login {
      border: 0;
      border-radius: 13px;
      padding: 14px;
      background: linear-gradient(110deg, #0b6657, #0b8069);
      color: white;
      font-weight: 800;
      box-shadow: 0 9px 20px #0b66572e;
      transition: .25s
    }

    .btn-login:hover {
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 13px 25px #0b665740
    }

    .login-footer {
      color: #8a9b93;
      font-size: 11px
    }

    .gold-line {
      width: 42px;
      height: 3px;
      border-radius: 4px;
      background: #d7b35f;
      margin: 18px auto
    }

    @media(max-width:480px) {
      .login-wrap {
        padding: 16px
      }

      .login-card {
        border-radius: 22px
      }

      .login-title {
        font-size: 22px
      }
    }
  </style>
</head>

<body>
  <div class="login-wrap">
    <div class="login-card">
      <div class="text-center mb-4">
        <h1 class="login-title mb-2">e-RAPOR</h1>
        <p class="login-subtitle mb-0">Sistem Informasi Penilaian dan Rapor</p>
        <div class="gold-line"></div>
      </div>
      @if($errors->any())<div class="alert alert-danger py-2 small rounded-3"><i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}</div>@endif
      <form method="POST" action="{{ route('login.process') }}">@csrf
        <div class="mb-3"><label class="form-label">Username</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-person"></i></span><input name="username" class="form-control" value="{{ old('username') }}" required autofocus placeholder="Masukkan username"></div>
        </div>
        <div class="mb-3"><label class="form-label">Password</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-lock"></i></span><input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password"><button class="input-group-text" type="button" id="togglePassword" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button></div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label small text-secondary" for="remember">Ingat saya</label></div><span class="small text-secondary"><i class="bi bi-shield-lock me-1"></i>Akses aman</span>
        </div>
        <button class="btn btn-login w-100" type="submit">Masuk ke Sistem <i class="bi bi-arrow-right ms-2"></i></button>
      </form>
      <div class="text-center mt-4 login-footer">Digitalisasi •󠁏 Transparan •󠁏 Terpercaya<br>© {{ date('Y') }} e-Rapor Sekolah Swasta</div>
    </div>
  </div>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const p = document.getElementById('password');
      const i = this.querySelector('i');
      p.type = p.type === 'password' ? 'text' : 'password';
      i.classList.toggle('bi-eye');
      i.classList.toggle('bi-eye-slash')
    });
  </script>
</body>

</html>