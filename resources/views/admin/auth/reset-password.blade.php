<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MPG — Reset Password</title>
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
    .wrap{min-height:100dvh; display:grid; place-items:center; padding:28px}
    .shell{
      width:min(520px,96vw); background:var(--panel); border-radius:24px; box-shadow:var(--shadow);
      border:1px solid rgba(255,255,255,.06); padding:48px 40px;
    }
    h1{margin:0 0 8px; font-size:24px; font-weight:800; color:var(--brand)}
    .sub{color:var(--muted); font-size:13.5px; margin-bottom:28px}
    label{display:block; font-size:12.5px; font-weight:600; color:var(--muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; margin-top:18px}
    input[type=email], input[type=password]{
      width:100%; padding:13px 16px; border-radius:12px;
      border:1.5px solid var(--line); background:#1e1c35; color:var(--ink);
      font-size:15px; outline:none; transition:border .2s;
    }
    input:focus{border-color:var(--brand)}
    .btn{
      width:100%; margin-top:28px; padding:14px; border-radius:12px; border:none; cursor:pointer;
      background:linear-gradient(90deg, var(--brand), var(--accent));
      color:#fff; font-size:15px; font-weight:700; letter-spacing:.3px;
    }
    .btn:hover{opacity:.9}
    .err{color:#ff8e8e; font-size:12.5px; margin-top:5px}
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
