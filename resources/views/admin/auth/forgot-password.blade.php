<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MPG — Forgot Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --primary:#0f766e; --primary-dk:#115e59;
      --bg:#f5f7fa; --card:#fff; --border:#e5e9f0;
      --text:#0f172a; --muted:#64748b; --danger:#dc2626; --success:#10b981;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background:
        radial-gradient(900px 600px at 8% 10%, #ecfeff 0%, transparent 60%),
        radial-gradient(900px 600px at 92% 90%, #ecfdf5 0%, transparent 60%),
        var(--bg);
      color:var(--text);
      font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      font-size:14px; line-height:1.55;
    }
    .globalBrand{position:fixed; left:20px; top:18px; z-index:5; display:flex; align-items:center; gap:10px}
    .globalBrand img{height:36px; border-radius:8px; background:#fff; padding:3px; border:1px solid var(--border)}
    .globalBrand a{color:var(--text); text-decoration:none; font-weight:600; font-size:12px; padding:6px 12px; border-radius:999px; border:1px solid var(--border); background:#fff}
    .globalBrand a:hover{border-color:var(--primary); color:var(--primary)}
    .wrap{min-height:100dvh; display:grid; place-items:center; padding:28px}
    .shell{
      width:min(460px,96vw); background:var(--card); border-radius:14px;
      box-shadow:0 20px 50px rgba(15,23,42,.08); border:1px solid var(--border);
      padding:36px 32px;
    }
    h1{margin:0 0 6px; font-size:22px; font-weight:800; color:var(--text); letter-spacing:-.3px}
    .sub{color:var(--muted); font-size:13px; margin-bottom:22px}
    label{display:block; font-size:12px; font-weight:700; color:var(--text); margin-bottom:6px}
    input[type=email]{
      width:100%; padding:11px 14px; border-radius:10px;
      border:1px solid var(--border); background:#fff; color:var(--text);
      font-size:14px; outline:none;
      transition:border-color .15s ease, box-shadow .15s ease;
      font-family:inherit;
    }
    input[type=email]:focus{border-color:var(--primary); box-shadow:0 0 0 3px rgba(15,118,110,.12)}
    .btn{
      width:100%; margin-top:18px; padding:12px; border-radius:10px; border:none; cursor:pointer;
      background:var(--primary); color:#fff; font-size:14px; font-weight:700;
      font-family:inherit;
      transition:background .15s ease, box-shadow .15s ease;
    }
    .btn:hover{background:var(--primary-dk); box-shadow:0 8px 20px rgba(15,118,110,.18)}
    .status{padding:10px 14px; border-radius:10px; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; font-size:13px; margin-bottom:16px}
    .err{color:var(--danger); font-size:12px; margin-top:5px}
    .back{display:block; text-align:center; margin-top:16px; color:var(--muted); font-size:13px; text-decoration:none}
    .back:hover{color:var(--primary)}
  </style>
</head>
<body>
<div class="globalBrand">
  <img src="/storage/uploads/logo3.png" alt="MPG" onerror="this.style.display='none'">
  <a href="{{ route('admin.login_form') }}">← Back to Login</a>
</div>

<div class="wrap">
  <div class="shell">
    <h1>Forgot Password?</h1>
    <p class="sub">तपाईंको work email दिनुहोस् — हामी पासवर्ड रिसेट लिंक पठाउनेछौँ।</p>

    @if (session('status'))
      <div class="status">✓ {{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.password.email') }}">
      @csrf
      <label for="email">Work Email</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}"
             placeholder="you@mpg.com.np" required autofocus>
      @error('email') <div class="err">{{ $message }}</div> @enderror

      <button class="btn" type="submit">Send Reset Link</button>
    </form>

    <a class="back" href="{{ route('admin.login_form') }}">Remember your password? Log in</a>
  </div>
</div>
</body>
</html>
