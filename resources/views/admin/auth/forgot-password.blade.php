<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MPG — Forgot Password</title>
  <meta name="color-scheme" content="dark"/>
  <style>
    :root{
      --bg:#2a2742; --panel:#3a3658; --card:#2f2b49;
      --ink:#f1f2f9; --muted:#b6b3c9; --line:#4a4667;
      --brand:#7c5cff; --accent:#5bd0ff; --radius:18px;
      --shadow:0 30px 80px rgba(16,14,30,.45), 0 10px 30px rgba(16,14,30,.35);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background:radial-gradient(978px 684px at 4% 10%, #f5f7ff 0%, transparent 60%),
        radial-gradient(900px 650px at 85% 20%, #d1ecff70 0%, transparent 60%),
        linear-gradient(135deg, #f0f4ff, #e6efff);
      color:var(--ink);
      font:15px/1.6 ui-sans-serif,system-ui,Segoe UI,Roboto,Arial;
    }
    .globalBrand{position:fixed; left:20px; top:18px; z-index:5; display:flex; align-items:center; gap:10px}
    .globalBrand img{height:42px}
    .globalBrand a{color:#e7e4ff; text-decoration:none; font-weight:800; font-size:12.5px; padding:6px 10px; border-radius:999px; border:1px solid #ffffff30; background:#00000030; backdrop-filter:blur(6px)}
    .wrap{min-height:100dvh; display:grid; place-items:center; padding:28px}
    .shell{
      width:min(520px,96vw); background:var(--panel); border-radius:24px; box-shadow:var(--shadow);
      border:1px solid rgba(255,255,255,.06); padding:48px 40px;
    }
    h1{margin:0 0 8px; font-size:24px; font-weight:800; color:var(--brand)}
    .sub{color:var(--muted); font-size:13.5px; margin-bottom:28px}
    label{display:block; font-size:12.5px; font-weight:600; color:var(--muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px}
    input[type=email]{
      width:100%; padding:13px 16px; border-radius:12px;
      border:1.5px solid var(--line); background:#1e1c35; color:var(--ink);
      font-size:15px; outline:none; transition:border .2s;
    }
    input[type=email]:focus{border-color:var(--brand)}
    .btn{
      width:100%; margin-top:24px; padding:14px; border-radius:12px; border:none; cursor:pointer;
      background:linear-gradient(90deg, var(--brand), var(--accent));
      color:#fff; font-size:15px; font-weight:700; letter-spacing:.3px;
    }
    .btn:hover{opacity:.9}
    .status{padding:12px 16px; border-radius:10px; background:#2a5c3f; color:#9fffce; font-size:13.5px; margin-bottom:20px}
    .err{color:#ff8e8e; font-size:12.5px; margin-top:5px}
    .back{display:block; text-align:center; margin-top:20px; color:var(--muted); font-size:13px; text-decoration:none}
    .back:hover{color:var(--accent)}
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
