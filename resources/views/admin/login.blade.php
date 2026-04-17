<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MPG — Team Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --primary:#0f766e;
      --primary-dk:#115e59;
      --accent:#38bdf8;
      --bg:#f5f7fa;
      --card:#ffffff;
      --border:#e5e9f0;
      --text:#0f172a;
      --muted:#64748b;
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
      font-size:14px;
      line-height:1.55;
    }

    .globalBrand{position:fixed; left:20px; top:18px; z-index:5; display:flex; align-items:center; gap:10px}
    .globalBrand img{height:36px; border-radius:8px; background:#fff; padding:3px; border:1px solid var(--border)}
    .globalBrand a{color:var(--text); text-decoration:none; font-weight:600; font-size:12px; padding:6px 12px; border-radius:999px; border:1px solid var(--border); background:#fff}
    .globalBrand a:hover{border-color:var(--primary); color:var(--primary)}

    .wrap{min-height:100dvh; display:grid; place-items:center; padding:28px}
    .shell{
      width:min(1040px,96vw);
      background:var(--card);
      border-radius:20px;
      box-shadow:0 30px 80px rgba(15,23,42,.18), 0 2px 6px rgba(15,23,42,.04);
      border:1px solid rgba(15,23,42,.06);
      overflow:hidden;
      display:grid;
      grid-template-columns:1.05fr 1fr;
    }
    @media (max-width: 980px){ .shell{grid-template-columns:1fr} }

    /* LEFT — hero */
    .left{position:relative; background:#0f172a; min-height:540px; overflow:hidden}
    .slide, .slide-next{
      position:absolute; inset:0; background-size:cover; background-position:center;
      transition:opacity .9s ease;
    }
    .slide::after, .slide-next::after{
      content:""; position:absolute; inset:0;
      background:linear-gradient(135deg, rgba(15,118,110,.55) 0%, rgba(15,23,42,.55) 100%);
    }
    .slide{opacity:1}
    .slide-next{opacity:0}

    .caption{position:absolute; left:0; right:0; bottom:0; padding:28px; color:#fff; z-index:2}
    .caption h2{margin:0 0 6px; font-size:20px; font-weight:800; letter-spacing:-.2px}
    .cap-sub{font-size:13px; opacity:.9}
    .dots{display:flex; gap:6px; margin-top:12px}
    .dot{width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,.4)}
    .dot.active{background:#fff; width:18px; border-radius:3px}

    .wx-chip{
      position:absolute; right:16px; top:16px; display:flex; align-items:center; gap:8px;
      padding:6px 12px; border-radius:999px;
      background:rgba(255,255,255,.95); backdrop-filter:blur(6px);
      border:1px solid rgba(255,255,255,.6);
      color:var(--text); font-size:12px; font-weight:600; z-index:2;
    }
    .wx-chip .t{font-weight:800; font-size:14px; color:var(--primary)}

    /* RIGHT — form */
    .right{padding:38px 36px; display:flex; flex-direction:column; justify-content:center; gap:14px; background:#fff}
    .brand-mark{display:flex; align-items:center; gap:10px; margin-bottom:6px}
    .brand-mark img{width:36px; height:36px; border-radius:8px; background:#ecfdf5; padding:4px; border:1px solid var(--border)}
    .brand-mark span{font-weight:800; font-size:15px; color:var(--text)}
    .head h1{
      margin:0; font-size:24px; font-weight:800; color:var(--text); letter-spacing:-.4px;
    }
    .meta{font-size:13px; color:var(--muted); margin-top:4px}
    .meta a{color:var(--primary); text-decoration:none; font-weight:700}

    form{display:grid; gap:14px; margin-top:14px}
    .field label{display:block; font-size:12px; color:var(--text); font-weight:700; margin-bottom:6px}
    .field input{
      width:100%;
      padding:11px 14px;
      border-radius:10px;
      background:#fff;
      border:1px solid var(--border);
      color:var(--text);
      font-size:14px;
      outline:none;
      transition:border-color .15s ease, box-shadow .15s ease;
    }
    .field input::placeholder{color:#94a3b8}
    .field input:hover{ border-color:rgba(15,118,110,.30) }
    .field input:focus{ border-color:var(--primary); box-shadow:0 0 0 4px rgba(15,118,110,.14) }

    .row{display:flex; align-items:center; justify-content:space-between; font-size:12.5px; color:var(--muted); margin-top:-4px}
    .row label{display:inline-flex; align-items:center; gap:6px; cursor:pointer; font-weight:500}
    .row a{color:var(--primary); text-decoration:none; font-weight:700}
    .row a:hover{text-decoration:underline}

    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      background:linear-gradient(135deg,#0f766e 0%,#14b8a6 100%);
      color:#fff; border:0;
      padding:13px 16px;
      border-radius:11px;
      cursor:pointer;
      font-weight:700;
      font-size:14px;
      letter-spacing:.3px;
      box-shadow:0 8px 22px rgba(15,118,110,.28), inset 0 1px 0 rgba(255,255,255,.18);
      transition:transform .12s ease, box-shadow .2s ease, filter .2s ease;
      position:relative; overflow:hidden;
    }
    .btn::after{
      content:""; position:absolute; top:0; left:-60%;
      width:40%; height:100%;
      background:linear-gradient(120deg,transparent 0%,rgba(255,255,255,.28) 50%,transparent 100%);
      transform:skewX(-20deg);
      transition:left .55s ease;
      pointer-events:none;
    }
    .btn:hover{filter:brightness(1.06); box-shadow:0 12px 28px rgba(15,118,110,.34), inset 0 1px 0 rgba(255,255,255,.22)}
    .btn:hover::after{left:120%}
    .btn:active{transform:translateY(1px)}

    .err{color:#dc2626; font-size:12px; margin-top:4px}
    .fine{font-size:11.5px; color:var(--muted); text-align:center; margin-top:4px}
  </style>
</head>
<body>

<div class="globalBrand">
  <img src="/storage/uploads/logo3.png" alt="MPG Logo" onerror="this.style.display='none'">
  <a href="https://mpg.com.np">Back to website</a>
</div>

<main class="wrap">
  <div class="shell" role="main" aria-label="MPG login">
    <aside class="left" aria-label="Hero">
      <div class="slide" id="slideA" style="background-image:url('about:blank')"></div>
      <div class="slide-next" id="slideB" style="background-image:url('about:blank')"></div>

      <div class="wx-chip" id="wxChip">
        <span id="wxCity">Pokhara</span>
        <span class="t" id="wxTemp">--°C</span>
      </div>

      <div class="caption">
        <h2>Capturing Growth, Creating Impact</h2>
        <div class="cap-sub">Run bold campaigns. Measure every click. Scale with strategy.</div>
        <div class="dots"><div class="dot" id="d1"></div><div class="dot" id="d2"></div><div class="dot" id="d3"></div></div>
      </div>
    </aside>

    <section class="right" aria-label="Login form">
      <div class="brand-mark">
        <img src="/storage/uploads/logo3.png" alt="" onerror="this.style.display='none'">
        <span>MPG Solution</span>
      </div>
      <div class="head">
        <h1>Welcome back</h1>
        <div class="meta">Need access? <a href="mailto:info@mpg.com.np">Contact admin</a></div>
      </div>

      <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="field">
          <label for="email">Work Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@mpg.com.np" required autofocus>
          @error('email') <div class="err">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
          @error('password') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label><input type="checkbox" name="remember"> Remember me</label>
          <a href="{{ route('admin.password.request') }}">Forgot password?</a>
        </div>

        <button class="btn" type="submit">Sign in</button>
        <div class="fine">Internal use • Authorized personnel only</div>
      </form>
    </section>
  </div>
</main>

<script>
(function(){
  const city = encodeURIComponent('Pokhara,NP');
  const url  = `{{ route('api.weather') }}?city=${city}`;
  fetch(url).then(r=>r.json()).then(d=>{
    document.getElementById('wxCity').textContent = d.city || 'Pokhara';
    document.getElementById('wxTemp').textContent = (d.temp!=null) ? (parseInt(d.temp,10)+'°C') : '--°C';
  }).catch(()=>{});
})();

(function(){
  const IMGS = [
    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=1600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=1600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?q=80&w=1600&auto=format&fit=crop'
  ];

  const a = document.getElementById('slideA');
  const b = document.getElementById('slideB');
  const dots = [document.getElementById('d1'), document.getElementById('d2'), document.getElementById('d3')];

  let idx = Math.floor(Math.random()*IMGS.length);
  let next = (idx+1) % IMGS.length;

  function setDots(i){
    dots.forEach(d=>d.classList.remove('active'));
    dots[i % dots.length].classList.add('active');
  }

  a.style.backgroundImage = `url('${IMGS[idx]}')`;
  b.style.backgroundImage = `url('${IMGS[next]}')`;
  setDots(idx);

  function swap(){
    b.style.backgroundImage = `url('${IMGS[next]}')`;
    b.style.opacity = 1;
    setTimeout(()=>{
      a.style.backgroundImage = `url('${IMGS[next]}')`;
      b.style.opacity = 0;
    }, 900);
    idx = next;
    next = (next+1) % IMGS.length;
    setDots(idx);
  }
  setInterval(swap, 8000);
})();
</script>
</body>
</html>
