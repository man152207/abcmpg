<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | MPG Solution</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --primary:#0f766e; --primary-dk:#115e59;
            --bg:#f5f7fa; --card:#fff; --border:#e5e9f0;
            --text:#0f172a; --muted:#64748b; --danger:#dc2626;
        }
        *{box-sizing:border-box}
        body{
            margin:0; min-height:100vh;
            background:
              radial-gradient(900px 600px at 8% 10%, #ecfeff 0%, transparent 60%),
              radial-gradient(900px 600px at 92% 90%, #ecfdf5 0%, transparent 60%),
              var(--bg);
            font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,sans-serif;
            color:var(--text);
            display:flex; align-items:center; justify-content:center;
            padding:24px;
        }
        .card{
            width:100%; max-width:440px;
            background:var(--card);
            border:1px solid var(--border);
            border-radius:14px;
            box-shadow:0 20px 50px rgba(15,23,42,.08);
            padding:32px;
        }
        h2{
            margin:0 0 6px; font-size:22px; font-weight:800;
            color:var(--text); text-align:center;
        }
        .sub{text-align:center; color:var(--muted); font-size:13px; margin-bottom:22px}
        .field{margin-bottom:14px}
        label{display:block; font-size:12px; font-weight:700; margin-bottom:6px; color:var(--text)}
        input{
            width:100%; padding:10px 12px;
            border:1px solid var(--border);
            border-radius:9px;
            font-size:14px;
            color:var(--text);
            background:#fff;
            font-family:inherit;
            transition:border-color .15s ease, box-shadow .15s ease;
        }
        input:focus{outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(15,118,110,.12)}
        .err{color:var(--danger); font-size:12px; margin-top:4px; font-style:italic}
        button{
            width:100%; padding:11px 16px;
            background:var(--primary); color:#fff;
            border:0; border-radius:10px;
            font-weight:700; font-size:14px;
            cursor:pointer; margin-top:8px;
            transition:background .15s ease, box-shadow .15s ease;
            font-family:inherit;
        }
        button:hover{background:var(--primary-dk); box-shadow:0 8px 20px rgba(15,118,110,.18)}
    </style>
</head>
<body>
    <div class="card">
        <h2>Admin Registration</h2>
        <div class="sub">Create your team account</div>

        <form method="POST" action="{{ route('admin.register') }}">
            @csrf

            <div class="field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password') <p class="err">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="field">
                <label for="phone">Phone Number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                @error('phone') <p class="err">{{ $message }}</p> @enderror
            </div>

            <button type="submit">Create Account</button>
        </form>
    </div>
</body>
</html>
