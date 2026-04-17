<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MPG — Reset Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --primary:#0f766e; --primary-dk:#115e59;
      --bg:#f5f7fa; --card:#fff; --border:#e5e9f0;
      --text:#0f172a; --muted:#64748b; --danger:#dc2626;
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
    .wrap{min-height:100dvh; display:grid; place-items:center; padding:28px}
    .shell{
      width:min(460px,96vw); background:var(--card); border-radius:14px;
      box-shadow:0 20px 50px rgba(15,23,42,.08); border:1px solid var(--border);
      padding:36px 32px;
    }
    h1{margin:0 0 6px; font-size:22px; font-weight:800; color:var(--text); letter-spacing:-.3px}
    .sub{color:var(--muted); font-size:13px; margin-bottom:22px}
    label{display:block; font-size:12px; font-weight:700; color:var(--text); margin-bottom:6px; margin-top:14px}
    input[type=email], input[type=password]{
      width:100%; padding:11px 14px; border-radius:10px;
      border:1px solid var(--border); background:#fff; color:var(--text);
      font-size:14px; outline:none;
      transition:border-color .15s ease, box-shadow .15s ease;
      font-family:inherit;
    }
    input:focus{border-color:var(--primary); box-shadow:0 0 0 3px rgba(15,118,110,.12)}
    input[readonly]{background:#f8fafc; color:var(--muted)}
    .btn{
      width:100%; margin-top:22px; padding:12px; border-radius:10px; border:none; cursor:pointer;
      background:var(--primary); color:#fff; font-size:14px; font-weight:700;
      font-family:inherit;
      transition:background .15s ease, box-shadow .15s ease;
    }
    .btn:hover{background:var(--primary-dk); box-shadow:0 8px 20px rgba(15,118,110,.18)}
    .err{color:var(--danger); font-size:12px; margin-top:5px}
    .hint{color:var(--muted); font-size:12px; margin-top:5px}
  </style>
</head>
<body>
<div class="globalBrand">
  <img src="/storage/uploads/logo3.png" alt="MPG" onerror="this.style.display='none'">
</div>

<div class="wrap">
  <div class="shell">
    <h1>Set New Password</h1>
    <p class="sub">तपाईंको नयाँ पासवर्ड तल दिनुहोस्।</p>

    <form method="POST" action="{{ route('admin.password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <label for="email">Email</label>
      <input id="email" type="email" name="email"
             value="{{ old('email', $email ?? '') }}" required readonly>
      @error('email') <div class="err">{{ $message }}</div> @enderror

      <label for="password">New Password</label>
      <input id="password" type="password" name="password"
             placeholder="Minimum 8 characters" required>
      <div class="hint">कम्तीमा 8 अक्षर हुनुपर्छ।</div>
      @error('password') <div class="err">{{ $message }}</div> @enderror

      <label for="password_confirmation">Confirm Password</label>
      <input id="password_confirmation" type="password" name="password_confirmation"
             placeholder="Repeat new password" required>

      <button class="btn" type="submit">Reset Password</button>
    </form>
  </div>
</div>
</body>
</html>
