<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>MPG — Team Login</title>
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
      margin:0; background:
  radial-gradient(978px 684px at 4% 10%, #f5f7ff 0%, transparent 60%),
  radial-gradient(900px 650px at 85% 20%, #d1ecff70 0%, transparent 60%),
  linear-gradient(135deg, #f0f4ff, #e6efff);
      color:var(--ink);
      font:15px/1.6 ui-sans-serif,system-ui,Segoe UI,Roboto,Arial;
    }

    /* Logo बाहिर (top-left) */
    .globalBrand{position:fixed; left:20px; top:18px; z-index:5; display:flex; align-items:center; gap:10px}
    .globalBrand img{height:42px}
    .globalBrand a{color:#e7e4ff; text-decoration:none; font-weight:800; font-size:12.5px; padding:6px 10px; border-radius:999px; border:1px solid #ffffff30; background:#00000030; backdrop-filter:blur(6px)}

    /* Centered shell */
    .wrap{min-height:100dvh; display:grid; place-items:center; padding:28px}
    .shell{
      width:min(1060px,96vw); background:var(--panel); border-radius:24px; box-shadow:var(--shadow);
      border:1px solid rgba(255,255,255,.06); overflow:hidden;
      display:grid; grid-template-columns:1.1fr 1fr;
    }
    @media (max-width: 980px){ .shell{grid-template-columns:1fr} }

    /* LEFT — hero slideshow */
    .left{position:relative; background:#1a1a2e; min-height:580px}
    .slide, .slide-next{
      position:absolute; inset:0; background-size:cover; background-position:center;
      transition:opacity .9s ease; filter:saturate(1.05) contrast(1.03);
    }
    .slide::after, .slide-next::after{
      content:""; position:absolute; inset:0;
      background:linear-gradient(0deg, rgba(22,20,38,.68), rgba(22,20,38,.22));
    }
    .slide{opacity:1}
    .slide-next{opacity:0}

    .caption{position:absolute; left:0; right:0; bottom:0; padding:28px; color:#ecebff}
    .caption h2{margin:0 0 6px; font-size:22px; font-weight:900; letter-spacing:.2px}
    .cap-sub{font-size:13.5px; color:#d8d6f1}
    .dots{display:flex; gap:6px; margin-top:8px}
    .dot{width:8px; height:8px; border-radius:50%; background:#aaa3ff55; border:1px solid #c3beff77}
    .dot.active{background:#fff; border-color:#fff}

    /* small weather chip */
    .wx-chip{
      position:absolute; right:16px; top:16px; display:flex; align-items:center; gap:10px;
      padding:8px 10px; border-radius:12px; background:rgba(15,17,40,.50); backdrop-filter: blur(6px);
      border:1px solid rgba(255,255,255,.18); color:#f4f6ff; font-size:13px
    }
    .wx-chip .t{font-weight:900; font-size:20px}

    /* RIGHT — login form */
    .right{padding:28px 28px 26px; background:var(--card); display:grid; align-content:start; gap:14px}
    .head h1{
      margin:0; font-size:24px; font-weight:1000; letter-spacing:.3px;
      background:linear-gradient(90deg, var(--brand), var(--accent));
      -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    }
    .meta{font-size:13px; color:var(--muted)}
    .meta a{color:#cfe4ff; text-decoration:none; font-weight:800}

    form{display:grid; gap:12px; margin-top:6px}
    label{font-size:13px; color:#d6d3ea; font-weight:800}
    input{
      width:100%; padding:12px 14px; border-radius:12px; background:#252244;
      border:1px solid #47426b; color:var(--ink); font-size:15px; outline:none;
    }
    input::placeholder{color:#8f89b0}
    input:focus{ border-color:#89dcff; box-shadow:0 0 0 3px #5bd0ff33 }

    .row{display:flex; align-items:center; justify-content:space-between; font-size:13px; color:#c5c1da}
    .row a{color:#bfe1ff; text-decoration:none; font-weight:800}

    .btn{
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      background:linear-gradient(90deg, var(--brand), var(--accent));
      color:#0a0e1f; border:0; padding:12px 16px; border-radius:12px; cursor:pointer;
      font-weight:900; letter-spacing:.35px; box-shadow:0 16px 32px rgba(91,92,255,.25);
    }

    .fine{font-size:12px; color:#bebad3; text-align:center; margin-top:6px}
  </style>
</head>
<body>

<!-- Logo बाहिर -->
<div class="globalBrand">
  <img src="/storage/uploads/logo3.png" alt="MPG Logo">
  <a href="https://mpg.com.np">Back to website</a>
</div>

<main class="wrap">
  <div class="shell" role="main" aria-label="MPG login">
    <!-- LEFT -->
    <aside class="left" aria-label="Hero">
      <!-- slideshow layers -->
      <div class="slide" id="slideA" style="background-image:url('about:blank')"></div>
      <div class="slide-next" id="slideB" style="background-image:url('about:blank')"></div>

      <!-- small Pokhara weather chip -->
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

    <!-- RIGHT -->
    <section class="right" aria-label="Login form">
      <div class="head">
        <h1>Login</h1>
        <div class="meta">Need access? <a href="mailto:info@mpg.com.np">Contact admin</a></div>
      </div>

      <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div>
          <label for="email">Work Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@mpg.com.np" required autofocus>
          @error('email') <div style="color:#ff8e8e; font-size:12.5px">{{ $message }}</div> @enderror
        </div>
        <div>
          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
          @error('password') <div style="color:#ff8e8e; font-size:12.5px">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label><input type="checkbox" name="remember"> Remember me</label>
          <a href="#">Forgot password?</a>
        </div>

        <button class="btn" type="submit">Login</button>
        <div class="fine">Internal use • Authorized personnel</div>
      </form>
    </section>
  </div>
</main>

<script>
/* Pokhara weather chip */
(function(){
  const city = encodeURIComponent('Pokhara,NP');
  const url  = `{{ route('api.weather') }}?city=${city}`;
  fetch(url).then(r=>r.json()).then(d=>{
    document.getElementById('wxCity').textContent = d.city || 'Pokhara';
    document.getElementById('wxTemp').textContent = (d.temp!=null) ? (parseInt(d.temp,10)+'°C') : '--°C';
  }).catch(()=>{});
})();

/* Random image + Auto slideshow (8s) */
(function(){
  // High-quality Unsplash images (royalty-free)
  const IMGS = [
    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1600&auto=format&fit=crop', // team / workspace
    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1600&auto=format&fit=crop', // tech desk
    'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=1600&auto=format&fit=crop', // neon city
    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1600&auto=format&fit=crop', // creative team
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop', // abstract dunes
    'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=1600&auto=format&fit=crop', // gradient wave
    'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?q=80&w=1600&auto=format&fit=crop'  // analytics screen
  ];

  const a = document.getElementById('slideA');
  const b = document.getElementById('slideB');
  const dots = [document.getElementById('d1'), document.getElementById('d2'), document.getElementById('d3')];

  // start with a random index
  let idx = Math.floor(Math.random()*IMGS.length);
  let next = (idx+1) % IMGS.length;

  function setDots(i){
    dots.forEach(d=>d.classList.remove('active'));
    dots[i % dots.length].classList.add('active');
  }

  // initial images
  a.style.backgroundImage = `url('${IMGS[idx]}')`;
  b.style.backgroundImage = `url('${IMGS[next]}')`;
  setDots(idx);

  function swap(){
    // crossfade
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

  // rotate every 8s
  setInterval(swap, 8000);
})();
</script>
</body>
</html>
