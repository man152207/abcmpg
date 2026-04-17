@php
use App\Models\UserPrivilege;
use Carbon\Carbon;
use App\Models\Ad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

$today = Carbon::today();

/** ====== TODAY SUMMARY (same logic) ====== */
$totalNPR = Ad::whereDate('created_at', $today)->sum('NRP');
$totalUSD = Ad::whereDate('created_at', $today)->sum('USD');

/** ====== USER / PRIVILEGE / RECEPTION FLAGS (same logic) ====== */
$adminUser = auth('admin')->user();

$_privRow = UserPrivilege::select('full_or_partial','option')
  ->where('user_id', $adminUser?->id)->first();

$isSuperAdmin = (bool)($_privRow->full_or_partial ?? 0);

$userPrivileges = $isSuperAdmin
  ? [1,2,3,4,5,6,7]
  : array_values(array_filter(array_map('intval', explode(',', $_privRow->option ?? ''))));

$inReception = false;
if ($adminUser) {
  $inReception = DB::table('admin_department')
    ->join('departments','admin_department.department_id','=','departments.id')
    ->where('admin_department.admin_id',$adminUser->id)
    ->where(function($q){
      $q->where('departments.slug','reception')->orWhere('departments.name','Reception');
    })->exists();
}

$canSeeReception = $isSuperAdmin || $inReception;
$isReceptionOnly = $inReception && !$isSuperAdmin;

/** ====== ASSETS: QR + Audio (cached for speed) ====== */
$qrImages = Cache::remember('mpg_qr_images_v1', 300, function(){
  return File::glob(public_path('images/qrs').'/*');
});

/** ====== BANK DATA (fast copy via JS object) ====== */
$bankData = [
  'GBL PAC' => "Bank Details:\nA/C Holder Name: MAN PRASAD GURUNG\nAccount Number: 06507010002936\nBank Name: GLOBAL IME BANK LTD.",
  'GBL BAC' => "Bank Details:\nA/C Holder Name: MPG SOLUTION PRIVATE LIMITED\nAccount Number: 06501010005708\nBank Name: GLOBAL IME BANK LTD.",
  'ADBL BAC' => "Bank Details:\nA/C Holder Name: MPG SOLUTION PVT LTD\nAccount Number: 0329005385010012\nBank Name: AGRICULTURAL DEVELOPMENT BANK\nBank Branch: Chauthe Branch",
  'SiDrth'  => "Bank Details:\nA/C Holder Name: PASCHIM POKHARA MEDIA PRIVATE LIMITED\nAccount Number: 00515148144\nBank Name: Siddhartha Bank Limited\nBank Branch: BAGAR",
  'SajhaGBL'=> "Bank Details:\nA/C Holder Name: SAJHA SUVIDHA PVT. LTD.\nAccount Number: 01401010010520\nBank Name: Global IME Bank Limited\nBank Branch: Newroad, Pokhara",
];

@endphp

<!DOCTYPE html>
<html lang="en" class="mpg-layout">
<head>
  @stack('styles')
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'MPG Solution | Admin Dashboard')</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    :root{
      --mpg-primary:#093b7b;
      --mpg-secondary:#646564;
      --mpg-accent:#ff7e5f;
      --mpg-accent2:#feb47b;
      --mpg-bg:#f7fafc;
      --mpg-text:#1f2937;
      --mpg-card:#ffffff;
      --mpg-border:#e5e7eb;
      --mpg-shadow:0 10px 24px rgba(15,23,42,.10);
      --mpg-radius:14px;
    }

    body.mpg-layout{
      font-family:'Source Sans Pro', sans-serif;
      background:var(--mpg-bg);
      color:var(--mpg-text);
      margin:0;
      line-height:1.4;
    }

    /* Sidebar */
    .mpg-layout .main-sidebar{
      background:linear-gradient(180deg,var(--mpg-primary) 0%,var(--mpg-secondary) 100%);
      box-shadow:3px 0 10px rgba(0,0,0,.08);
    }
    .mpg-layout .brand-link{
      background:linear-gradient(90deg,var(--mpg-primary) 0%,var(--mpg-secondary) 100%);
      color:#fff;
      padding:1rem 1.1rem;
      border-bottom:1px solid rgba(255,255,255,.15);
      display:flex;
      align-items:center;
      gap:.75rem;
    }
    .mpg-layout .brand-image{width:36px;height:36px;opacity:1;}
    .mpg-layout .nav-sidebar .nav-link{
      color:#fff;
      font-weight:600;
      border-radius:10px;
      margin:.35rem .6rem;
      padding:.55rem .8rem;
      transition:.14s ease;
    }
    .mpg-layout .nav-sidebar .nav-icon{color:var(--mpg-accent2); margin-right:.8rem;}
    .mpg-layout .nav-sidebar .nav-link:hover{
      background:rgba(255,255,255,.12);
      transform:translateY(-1px);
      border-left:4px solid var(--mpg-accent2);
    }
    .mpg-layout .nav-sidebar .nav-link.active{
      background:var(--mpg-accent);
      border-left:4px solid var(--mpg-accent2);
      box-shadow:0 8px 16px rgba(0,0,0,.18);
    }
    .mpg-layout .nav-header{color:rgba(255,255,255,.75);}

    /* Navbar */
    .mpg-layout .main-header.navbar{
      background:#fff;
      border-bottom:1px solid rgba(0,0,0,.05);
      box-shadow:0 2px 10px rgba(0,0,0,.08);
      padding:.55rem 1rem;
      position:sticky;
      top:0;
      z-index:1200;
    }
    .mpg-layout .mpg-chip{
      display:inline-flex; align-items:center;
      padding:.42rem .78rem;
      border-radius:999px;
      font-weight:600;
      font-size:.85rem;
      white-space:nowrap;
      border:1px solid var(--mpg-border);
      background:#fff;
    }
    .mpg-layout .mpg-chip-primary{border-color:rgba(9,59,123,.18)}
    .mpg-layout .mpg-chip-info{background:#17a2b8;color:#fff;border:0}
    .mpg-layout .mpg-action-btn{
      display:flex; align-items:center;
      padding:.48rem .7rem;
      margin:0 .12rem;
      border-radius:10px;
      color:#111827;
      font-weight:600;
      transition:.14s ease;
      border:1px solid transparent;
    }
    .mpg-layout .mpg-action-btn:hover{
      background:rgba(255,126,95,.10);
      color:var(--mpg-accent);
      border-color:rgba(255,126,95,.35);
    }
    .mpg-layout .mpg-action-btn.active{
      background:var(--mpg-accent);
      color:#fff;
    }
    .mpg-layout .mpg-user-dropdown{
      display:flex;align-items:center;
      gap:.5rem;
      padding:.42rem .7rem;
      border-radius:10px;
      background:var(--mpg-secondary);
      color:#fff;
      font-weight:700;
      text-decoration:none;
      transition:.14s ease;
    }
    .mpg-layout .mpg-user-dropdown:hover{background:var(--mpg-accent);color:#fff}
    .mpg-layout .mpg-user-avatar{width:28px;height:28px;object-fit:cover}

    /* Weather small box */
    .mpg-layout .wxbox{
      display:inline-flex;align-items:center;gap:.4rem;
      padding:.42rem .7rem;border-radius:999px;
      background:#111827;color:#fff;font-weight:700;
      border:1px solid rgba(255,255,255,.08);
      font-size:.85rem;
    }

    /* QR Modal */
    #qrOverlay{
      position:fixed; inset:0;
      background:rgba(0,0,0,.50);
      backdrop-filter:blur(4px) saturate(140%);
      display:none;
      z-index:2500;
      align-items:center;
      justify-content:center;
      padding:16px;
    }
    #qrModal{
      width:100%;
      max-width:900px;
      background:#fff;
      border-radius:20px;
      box-shadow:0 24px 48px rgba(0,0,0,.35);
      overflow:hidden;
      border:1px solid rgba(0,0,0,.06);
      max-height:82vh;
      display:flex;
      flex-direction:column;
    }
    #qrModalHead{
      display:flex;align-items:flex-start;justify-content:space-between;
      padding:12px 16px;
      background:linear-gradient(135deg,var(--mpg-primary) 0%,var(--mpg-secondary) 100%);
      color:#fff;
    }
    #qrCloseBtn{
      border:0;
      background:rgba(255,255,255,.14);
      color:#fff;
      padding:6px 10px;
      border-radius:10px;
      font-weight:800;
      cursor:pointer;
    }
    #qrModalBody{padding:16px; overflow:auto;}
    .qr-grid{
      display:grid;
      grid-template-columns:repeat(auto-fill,minmax(170px,1fr));
      gap:14px;
    }
    .qr-item{
      background:#fff;
      border:1px solid var(--mpg-border);
      border-radius:16px;
      padding:14px;
      box-shadow:var(--mpg-shadow);
      cursor:pointer;
      transition:.14s ease;
      text-align:center;
    }
    .qr-item:hover{
      transform:translateY(-2px);
      border-color:rgba(255,126,95,.55);
      box-shadow:0 18px 34px rgba(15,23,42,.16);
    }
    .qr-item img{
      width:100%;
      max-width:210px;
      max-height:210px;
      object-fit:contain;
      border-radius:12px;
      border:1px solid rgba(0,0,0,.08);
      background:#fff;
    }
    .qr-label{margin-top:10px;font-weight:800;font-size:.82rem;color:#0f172a}
    .qr-hint{margin-top:10px;text-align:center;font-size:.75rem;color:#64748b}

        /* Navbar responsive collapse */
    @media (max-width: 991.98px){
      #mpgNavbarCollapse{
        position:absolute;
        top:100%;
        left:0; right:0;
        background:#fff;
        border-top:1px solid rgba(0,0,0,.08);
        box-shadow:0 10px 24px rgba(0,0,0,.10);
        padding:12px;
        max-height:70vh;
        overflow:auto;
        z-index:2000;
      }
      .mpg-layout .mpg-action-btn{
        justify-content:flex-start;
        border:1px solid rgba(0,0,0,.08);
        margin:.15rem 0;
      }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed mpg-layout">
<div class="wrapper mpg-layout">

  {{-- ========== TOP NAVBAR ========== --}}
  <nav class="main-header navbar navbar-expand-lg navbar-white navbar-light mpg-layout">
    <div class="d-flex align-items-center">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
        <li class="nav-item d-none d-lg-inline-block">
          <a href="{{ $isReceptionOnly ? route('recp.dashboard') : route('admin.dashboard') }}" class="nav-link">Home</a>
        </li>
      </ul>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#mpgNavbarCollapse" aria-controls="mpgNavbarCollapse"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mpgNavbarCollapse">
      @if(!$isReceptionOnly)
        <div class="mx-lg-auto my-2 my-lg-0">
          <div class="d-flex flex-wrap align-items-center justify-content-center" style="gap:.4rem;">
            <span class="mpg-chip mpg-chip-primary">
              Today: ${{ number_format($totalUSD, 2, '.', ',') }}
            </span>
            <span class="mpg-chip mpg-chip-primary">
              Today: Rs.{{ number_format((float)$totalNPR, 2, '.', ',') }}
            </span>
            <a href="{{ url('/admin/dashboard/ads/summary') }}" class="mpg-chip mpg-chip-info">
              All Summary
            </a>
            <div class="wxbox">
              <span id="wxIcon">⛅</span>
              <strong id="wxCity">Pokhara</strong> •
              <strong id="wxTemp">--°C</strong> •
              <strong id="wxTime">--:--</strong>
            </div>
          </div>
        </div>
      @endif

      <ul class="navbar-nav ml-auto mpg-nav-actions">
        @if(!$isReceptionOnly)
          {{-- Bonus Season --}}
          <li class="nav-item dropdown">
            <a class="nav-link mpg-action-btn" data-toggle="dropdown" href="#" id="bonusSeasonDropdown">
              <i class="fa-solid fa-gift"></i>
              <span class="d-none d-lg-inline ml-1">Bonus</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3" style="min-width:260px;">
              <div class="mb-2" style="font-size:.85rem;">
                <strong>Bonus Season</strong><br>
                <span id="bonusStatus" class="text-muted">Loading...</span>
              </div>
              <form id="bonusSeasonForm">
                <div class="form-group mb-2">
                  <label for="bonusMinSpend" style="font-size:.8rem;">Minimum Spend (USD)</label>
                  <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                         id="bonusMinSpend" name="min_spend" placeholder="e.g. 300">
                </div>
                <div class="form-group mb-2">
                  <label for="bonusPercent" style="font-size:.8rem;">Bonus %</label>
                  <input type="number" step="0.01" min="0" max="1000" class="form-control form-control-sm"
                         id="bonusPercent" name="bonus_percent" placeholder="e.g. 10">
                </div>
                <div class="form-group mb-2">
                  <label for="bonusClaimDays" style="font-size:.8rem;">Bonus claim days (after season end)</label>
                  <input type="number" step="1" min="0" max="365" class="form-control form-control-sm"
                         id="bonusClaimDays" name="claim_days" placeholder="e.g. 7">
                </div>
                <div class="form-group mb-2">
                  <label for="bonusStart" style="font-size:.8rem;">Start date</label>
                  <input type="date" class="form-control form-control-sm" id="bonusStart" name="start_date" required>
                </div>
                <div class="form-group mb-3">
                  <label for="bonusEnd" style="font-size:.8rem;">End date</label>
                  <input type="date" class="form-control form-control-sm" id="bonusEnd" name="end_date" required>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" id="bonusDeactivateBtn" class="btn btn-outline-danger btn-sm">Turn off</button>
                  <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                </div>
              </form>
            </div>
          </li>

          {{-- Boosting --}}
          <li class="nav-item">
            <a href="{{ route('boosting.index') }}"
               class="nav-link mpg-action-btn {{ request()->routeIs('boosting.*') ? 'active' : '' }}">
              <i class="fa-solid fa-list-check"></i>
              <span class="d-none d-lg-inline ml-1">Boosting</span>
            </a>
          </li>

          {{-- Prompts --}}
          <li class="nav-item">
            <a href="{{ route('admin.prompts.index') }}"
               class="nav-link mpg-action-btn {{ request()->routeIs('admin.prompts.*') ? 'active' : '' }}">
              <i class="fa-solid fa-wand-magic-sparkles"></i>
              <span class="d-none d-lg-inline ml-1">Prompts</span>
            </a>
          </li>

          {{-- QR --}}
          <li class="nav-item">
            <a href="#" id="qrToggleBtn" class="nav-link mpg-action-btn">
              <i class="fa-solid fa-qrcode"></i>
              <span class="d-none d-lg-inline ml-1">QR</span>
            </a>
          </li>

          {{-- Banks --}}
          <li class="nav-item dropdown">
            <a class="nav-link mpg-action-btn" data-toggle="dropdown" href="#">
              <i class="fas fa-university"></i>
              <span class="d-none d-lg-inline ml-1">Banks</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="#" class="dropdown-item" data-bank="GBL PAC">GBL Personal</a>
              <a href="#" class="dropdown-item" data-bank="GBL BAC">GBL MPG Solution</a>
              <a href="#" class="dropdown-item" data-bank="ADBL BAC">ADBL MPG Solution</a>
              <a href="#" class="dropdown-item" data-bank="SiDrth">SiDrth Paschim Pokhara</a>
              <a href="#" class="dropdown-item" data-bank="SajhaGBL">GBL Sajha Suvidha</a>
            </div>
          </li>

          {{-- 2FA --}}
          <li class="nav-item">
            <a href="{{ route('admin.2fa.index') }}" class="nav-link mpg-action-btn">
              <i class="fas fa-shield-alt"></i>
              <span class="d-none d-lg-inline ml-1">2FA</span>
            </a>
          </li>
        @endif

        {{-- User Dropdown --}}
        @php
          $avatar = $adminUser && $adminUser->profile_picture
            ? (Str::startsWith($adminUser->profile_picture, ['http://','https://'])
              ? $adminUser->profile_picture
              : asset('storage/'.$adminUser->profile_picture))
            : asset('dist/img/user2-160x160.jpg');
        @endphp
        <li class="nav-item dropdown">
          <a class="nav-link mpg-user-dropdown" data-toggle="dropdown" href="#">
            <img src="{{ $avatar }}" class="img-circle elevation-2 mpg-user-avatar" alt="User Image">
            <span class="d-none d-lg-inline">{{ $adminUser?->name }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Profile
            </a>
            @if(!$isReceptionOnly && $isSuperAdmin)
              <a href="{{ route('admin.user.add') }}" class="dropdown-item">
                <i class="fas fa-user-plus mr-2"></i> Add User
              </a>
              <a href="{{ route('admin.user.show') }}" class="dropdown-item">
                <i class="fas fa-users mr-2"></i> List Users
              </a>
            @endif
            <div class="dropdown-divider"></div>
            <a href="{{ route('admin.logout') }}" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  {{-- ========= QR MODAL ========= --}}
  @if(!$isReceptionOnly)
  <div id="qrOverlay">
    <div id="qrModal">
      <div id="qrModalHead">
        <div>
          <div style="font-weight:900;font-size:.95rem;">Scan / Copy QR</div>
          <div style="font-size:.78rem;opacity:.8;">Tap any QR to copy. It will auto-close.</div>
        </div>
        <button id="qrCloseBtn" type="button">Close ✖</button>
      </div>
      <div id="qrModalBody">
        <div class="qr-grid" id="qrGrid">
          @foreach($qrImages as $image)
            @php
              $rel  = str_replace(public_path(), '', $image);
              $name = pathinfo($image, PATHINFO_FILENAME);
            @endphp
            <div class="qr-item" data-src="{{ $rel }}" data-name="{{ $name }}">
              <img src="{{ $rel }}" alt="{{ $name }}" loading="lazy" decoding="async">
              <div class="qr-label">{{ $name }}</div>
            </div>
          @endforeach
        </div>
        <div class="qr-hint">
          Works for direct paste into chat / WhatsApp.<br>
          If browser blocks image-copy, it will fallback to open image.
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- ========== SIDEBAR ========== --}}
  <aside class="main-sidebar sidebar-dark-primary elevation-4 mpg-layout">
    <a href="{{ $isReceptionOnly ? route('recp.dashboard') : '/admin/dashboard/ads_list' }}" class="brand-link mpg-layout">
      <img src="{{asset('dist/img/Brand-icon2.png')}}" alt="MPG" class="brand-image img-circle elevation-3 mpg-layout">
      <span class="brand-text font-weight-light mpg-layout" style="font-weight: 800;">MPG Solution</span>
    </a>

    <div class="sidebar mpg-layout">
      <nav class="mt-2 mpg-layout">
        <ul class="nav nav-pills nav-sidebar flex-column mpg-layout" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item mpg-layout">
            <a href="{{ $isReceptionOnly ? route('recp.dashboard') : route('admin.dashboard') }}"
               class="nav-link mpg-layout {{ request()->routeIs('admin.dashboard') || request()->routeIs('recp.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt mpg-layout"></i>
              <p class="mpg-layout">Dashboard</p>
            </a>
          </li>

          {{-- Reception --}}
          @if($canSeeReception)
            @php $recpActive = request()->is('admin/recp*') || request()->routeIs('recp.*'); @endphp
            <li class="nav-item has-treeview mpg-layout {{ $recpActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link mpg-layout {{ $recpActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-bell mpg-layout"></i>
                <p class="mpg-layout">Reception<i class="fas fa-angle-left right mpg-layout"></i></p>
              </a>
              <ul class="nav nav-treeview mpg-layout">
                <li class="nav-item mpg-layout">
                  <a href="{{ route('recp.dashboard') }}" class="nav-link mpg-layout {{ request()->routeIs('recp.dashboard') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Overview</p>
                  </a>
                </li>
                <li class="nav-item mpg-layout">
                  <a href="{{ route('recp.students.list') }}" class="nav-link mpg-layout {{ request()->routeIs('recp.students.list') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Students</p>
                  </a>
                </li>
                <li class="nav-item mpg-layout">
                  <a href="{{ route('recp.students.create') }}" class="nav-link mpg-layout {{ request()->routeIs('recp.students.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Add Student</p>
                  </a>
                </li>
                <li class="nav-item mpg-layout">
                  <a href="#" class="nav-link mpg-layout" onclick="return recpStudentEditPrompt();">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Edit Student</p>
                  </a>
                </li>
                <li class="nav-item mpg-layout">
                  <a href="#" class="nav-link mpg-layout" onclick="return recpEnrollPrompt();">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Enroll Student</p>
                  </a>
                </li>
                <li class="nav-item mpg-layout">
                  <a href="#" class="nav-link mpg-layout" onclick="return recpPaymentPrompt();">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Take Payment</p>
                  </a>
                </li>
                <li class="nav-item mpg-layout">
                  <a href="#" class="nav-link mpg-layout" onclick="return recpDocPrompt();">
                    <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">New Document</p>
                  </a>
                </li>
              </ul>
            </li>
          @endif

          @if(!$isReceptionOnly)

            {{-- Sales & CRM --}}
            @if(in_array(3, $userPrivileges))
              <li class="nav-header mpg-layout">Sales & CRM</li>

              <li class="nav-item mpg-layout">
                <a href="{{ route('admin.followups.index') }}"
                   class="nav-link mpg-layout {{ request()->routeIs('admin.followups.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-address-book mpg-layout"></i><p class="mpg-layout">Follow-Ups</p>
                </a>
              </li>

              <li class="nav-item mpg-layout">
                <a href="{{ route('customer.show') }}" class="nav-link mpg-layout">
                  <i class="nav-icon fas fa-users mpg-layout"></i><p class="mpg-layout">Customers</p>
                </a>
              </li>
  <li class="nav-item has-treeview mpg-layout">
    <a href="#" class="nav-link mpg-layout {{ request()->is('admin/smmx*') ? 'active' : '' }}">
      <i class="nav-icon fas fa-bullhorn mpg-layout"></i>
      <p class="mpg-layout">
        Social Media
        <i class="fas fa-angle-left right mpg-layout"></i>
      </p>
    </a>
    <ul class="nav nav-treeview mpg-layout">
      <li class="nav-item mpg-layout">
        <a href="{{ route('admin.smmx.customers.index') }}"
           class="nav-link mpg-layout {{ request()->routeIs('admin.smmx.customers.*') ? 'active' : '' }}">
          <i class="far fa-circle nav-icon mpg-layout"></i>
          <p class="mpg-layout">Customers Panel</p>
        </a>
      </li>

      <li class="nav-item mpg-layout">
        <a href="{{ route('admin.smmx.onboarding.index') }}"
           class="nav-link mpg-layout {{ request()->routeIs('admin.smmx.onboarding.*') ? 'active' : '' }}">
          <i class="far fa-circle nav-icon mpg-layout"></i>
          <p class="mpg-layout">Onboarding Brands</p>
        </a>
      </li>

    <li class="nav-item mpg-layout">
      <a href="{{ route('admin.smmx.deliverables.index') }}"
         class="nav-link mpg-layout {{ request()->routeIs('admin.smmx.deliverables.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon mpg-layout"></i>
        <p class="mpg-layout">Deliverables</p>
      </a>
    </li>

    <li class="nav-item mpg-layout">
      <a href="{{ route('admin.smmx.reports.index') }}"
         class="nav-link mpg-layout {{ request()->routeIs('admin.smmx.reports.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon mpg-layout"></i>
        <p class="mpg-layout">Reports</p>
      </a>
    </li>
  </ul>
</li>
              <li class="nav-item has-treeview mpg-layout">
                <a href="#" class="nav-link mpg-layout">
                  <i class="nav-icon fas fa-concierge-bell mpg-layout"></i>
                  <p class="mpg-layout">Quotation<i class="fas fa-angle-left right mpg-layout"></i></p>
                </a>
                <ul class="nav nav-treeview mpg-layout">
                  <li class="nav-item mpg-layout">
                    <a href="{{ route('quotation.generate') }}" class="nav-link mpg-layout">
                      <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Quotation Generator</p>
                    </a>
                  </li>
                  <li class="nav-item mpg-layout">
                    <a href="{{ route('item.show') }}" class="nav-link mpg-layout">
                      <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Service Management</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item mpg-layout">
                <a href="{{ url('/ad-management') }}" class="nav-link mpg-layout {{ request()->is('ad-management*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users mpg-layout"></i><p class="mpg-layout">AdAccounts</p>
                </a>
              </li>
            @endif

            {{-- Billing & Finance --}}
            @if(in_array(6, $userPrivileges) || in_array(7, $userPrivileges) || in_array(4, $userPrivileges))
              <li class="nav-header mpg-layout">Billing & Finance</li>

              @if(in_array(6, $userPrivileges))
                <li class="nav-item has-treeview mpg-layout">
                  <a href="#" class="nav-link mpg-layout">
                    <i class="nav-icon fas fa-file-invoice mpg-layout"></i>
                    <p class="mpg-layout">Invoice<i class="fas fa-angle-left right mpg-layout"></i></p>
                  </a>
                  <ul class="nav nav-treeview mpg-layout">
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('invoice.pendingBills') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Requires Bill</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('invoice.add') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">New Invoice</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('invoice.list') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Invoice List</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif

              @if(in_array(7, $userPrivileges))
                <li class="nav-item has-treeview mpg-layout">
                  <a href="#" class="nav-link mpg-layout">
                    <i class="nav-icon fas fa-money-bill mpg-layout"></i>
                    <p class="mpg-layout">Accounts<i class="fas fa-angle-left right mpg-layout"></i></p>
                  </a>
                  <ul class="nav nav-treeview mpg-layout">
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('card.show') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Manage</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('all_in_one') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">All Details</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('credit.show') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Credit Detail</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                      <a href="{{ route('credit.summary') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Credit Summary</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('debit.show') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Debit Detail</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                      <a href="{{ route('debit.summary') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Debit Summary</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif

              @if(in_array(4, $userPrivileges))
                <li class="nav-item has-treeview mpg-layout">
                  <a href="#" class="nav-link mpg-layout">
                    <i class="nav-icon fas fa-rupee-sign mpg-layout"></i>
                    <p class="mpg-layout">Expenditures<i class="fas fa-angle-left right mpg-layout"></i></p>
                  </a>
                  <ul class="nav nav-treeview mpg-layout">
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('client.add') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">New Purchase</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('client.show') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Purchased Details</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('exp.show') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Other Expenses</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                      <a href="{{ route('client_summary') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Expenses Summary</p>
                      </a>
                    </li>
                    <li class="nav-item mpg-layout">
                      <a href="{{ route('other_income.index') }}" class="nav-link mpg-layout">
                        <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Other Income</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif
            @endif

            {{-- Operations & Records --}}
            @if(in_array(2, $userPrivileges))
              <li class="nav-header mpg-layout">Operations & Records</li>

              <li class="nav-item has-treeview mpg-layout">
                <a href="#" class="nav-link mpg-layout">
                  <i class="nav-icon fa fa-book mpg-layout"></i>
                  <p class="mpg-layout">Record Book<i class="fas fa-angle-left right mpg-layout"></i></p>
                </a>
                <ul class="nav nav-treeview mpg-layout">
                  <li class="nav-item mpg-layout">
                    <a href="{{ route('ads.show') }}" class="nav-link mpg-layout">
                      <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Daily Records</p>
                    </a>
                  </li>
                  <li class="nav-item mpg-layout">
                    <a href="https://app.mpg.com.np/duty-schedule" target="_blank" rel="noopener noreferrer"
                       class="nav-link mpg-layout {{ request()->is('duty-schedule*') ? 'active' : '' }}">
                      <i class="fa fa-calendar-check nav-icon mpg-layout"></i><p class="mpg-layout">Duty Schedule</p>
                    </a>
                  </li>
                  <li class="nav-item mpg-layout">
                    <a href="{{ route('ads_complete.show') }}" class="nav-link mpg-layout">
                      <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Previous Records</p>
                    </a>
                  </li>
                  <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                    <a href="{{ route('ads.summary') }}" class="nav-link mpg-layout">
                      <i class="far fa-circle nav-icon mpg-layout"></i><p class="mpg-layout">Monthly Summary</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item mpg-layout">
                <a href="{{ route('admin.daily-logs.index') }}"
                   class="nav-link mpg-layout {{ request()->routeIs('admin.daily-logs.*') ? 'active' : '' }}">
                  <i class="fa fa-book nav-icon mpg-layout"></i><p class="mpg-layout">Daily Log Book</p>
                </a>
              </li>
            @endif

            {{-- Content & Assets --}}
            <li class="nav-header mpg-layout">Content & Assets</li>

            <li class="nav-item mpg-layout">
              <a href="{{ route('admin.multimedia.index') }}"
                 class="nav-link mpg-layout {{ request()->routeIs('admin.multimedia.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-photo-video mpg-layout"></i><p class="mpg-layout">Multimedia</p>
              </a>
            </li>

            <li class="nav-item mpg-layout">
              <a href="{{ url('/admin/packages') }}"
                 class="nav-link mpg-layout {{ request()->is('admin/packages*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes mpg-layout"></i><p class="mpg-layout">Packages</p>
              </a>
            </li>

            {{-- Communication --}}
            @if(in_array(3, $userPrivileges))
              <li class="nav-header mpg-layout">Communication</li>
              <li class="nav-item mpg-layout">
                <a href="{{ route('admin.chat.internal') }}" class="nav-link mpg-layout">
                  <i class="nav-icon fas fa-comments mpg-layout"></i><p class="mpg-layout">Chat</p>
                </a>
              </li>
            @endif

            <li class="nav-item mpg-layout">
              <a href="{{ route('admin.uscalendar.index') }}"
                 class="nav-link mpg-layout {{ request()->routeIs('admin.uscalendar.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock mpg-layout"></i><p class="mpg-layout">USA Calendar</p>
              </a>
            </li>

          @endif
        </ul>
      </nav>
    </div>
  </aside>

  {{-- ========== CONTENT ========== --}}
  <div class="content-wrapper mpg-layout">
    <div class="content-header mpg-layout" style="padding:0;margin-top:-7px;">
      <div class="container-fluid mpg-layout">
        <div class="row mb-2 mpg-layout">
          <div class="col-sm-6 mpg-layout"></div>
        </div>
      </div>
    </div>

    @yield('content')
  </div>

  <aside class="control-sidebar control-sidebar-dark mpg-layout"></aside>

  <footer class="main-footer mpg-layout">
    <strong>Copyright © 2017-{{ date('Y') }}
      <a href="https://mpgsolution.com">MPG Solution</a>.
    </strong>
    All rights reserved.
  </footer>

</div>

{{-- ========= CORE SCRIPTS ========= --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script> $.widget.bridge('uibutton', $.ui.button) </script>

@yield('js_')

<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.js')}}"></script>

@stack('scripts')

<script>
(function(){
  // ===== Toast =====
  window.showNotification = function(message){
    const n = document.createElement('div');
    n.innerText = message;
    n.style.position = 'fixed';
    n.style.bottom = '40px';
    n.style.right = '20px';
    n.style.backgroundColor = '#16a34a';
    n.style.color = '#fff';
    n.style.padding = '12px 18px';
    n.style.borderRadius = '10px';
    n.style.boxShadow = '0 12px 26px rgba(0,0,0,.18)';
    n.style.zIndex = '10000';
    document.body.appendChild(n);
    setTimeout(()=>n.remove(), 2200);
  };

  // ===== Clock =====
  const wxTime = document.getElementById('wxTime');
  function tick(){
    if(!wxTime) return;
    wxTime.textContent = new Intl.DateTimeFormat('en-GB',{
      hour:'2-digit',minute:'2-digit',second:'2-digit',
      hour12:false,timeZone:'Asia/Kathmandu'
    }).format(new Date());
  }
  tick(); setInterval(tick, 1000);

  // ===== Weather =====
  const url = `{{ route('api.weather') }}?city=Pokhara,NP`;
  fetch(url).then(r=>r.json()).then(d=>{
    const cityEl=document.getElementById('wxCity');
    const tempEl=document.getElementById('wxTemp');
    const iconEl=document.getElementById('wxIcon');
    if(cityEl) cityEl.textContent = d.city || 'Pokhara';
    if(tempEl) tempEl.textContent = (d.temp!=null) ? (parseInt(d.temp,10)+'°C') : '--°C';
    let condition=(d.condition||'').toLowerCase();
    let icon="⛅";
    if(condition.includes("clear")) icon="☀️";
    else if(condition.includes("cloud")) icon="☁️";
    else if(condition.includes("rain")) icon="🌧️";
    else if(condition.includes("thunder")) icon="⛈️";
    else if(condition.includes("snow")) icon="❄️";
    else if(condition.includes("wind")) icon="🌬️";
    else if(condition.includes("fog")||condition.includes("mist")) icon="🌫️";
    if(iconEl) iconEl.textContent=icon;
  }).catch(()=>{});

  // ===== Banks (FAST copy) =====
  const BANK_DATA = @json($bankData);
  document.addEventListener('click', async (e)=>{
    const bankEl = e.target.closest('[data-bank]');
    if(!bankEl) return;

    e.preventDefault();
    const key = bankEl.getAttribute('data-bank');
    const txt = BANK_DATA[key] || '';
    if(!txt) return;

    try{
      await navigator.clipboard.writeText(txt);
      showNotification(`Copied: ${key}`);
    }catch(err){
      // fallback
      const ta=document.createElement('textarea');
      ta.value=txt; document.body.appendChild(ta);
      ta.select(); document.execCommand('copy');
      ta.remove();
      showNotification(`Copied: ${key}`);
    }
  });

  // ===== QR Modal Open/Close (FAST) =====
  const qrToggleBtn = document.getElementById('qrToggleBtn');
  const qrOverlay = document.getElementById('qrOverlay');
  const qrCloseBtn = document.getElementById('qrCloseBtn');

  function openQr(){ if(qrOverlay) qrOverlay.style.display='flex'; }
  function closeQr(){ if(qrOverlay) qrOverlay.style.display='none'; }

  if(qrToggleBtn){
    qrToggleBtn.addEventListener('click', (e)=>{ e.preventDefault(); openQr(); });
  }
  if(qrCloseBtn){ qrCloseBtn.addEventListener('click', closeQr); }
  if(qrOverlay){
    qrOverlay.addEventListener('click', (e)=>{ if(e.target===qrOverlay) closeQr(); });
  }

  // copy image FAST: fetch -> blob -> clipboard (no canvas heavy)
  async function copyImageByUrl(url, label){
    try{
      const res = await fetch(url, {cache:'force-cache'});
      const blob = await res.blob();
      await navigator.clipboard.write([ new ClipboardItem({ [blob.type || 'image/png']: blob }) ]);
      showNotification(`Copied: ${label || 'Image'}`);
      return true;
    }catch(e){
      return false;
    }
  }

  // Event delegation for QR grid
  document.addEventListener('click', async (e)=>{
    const item = e.target.closest('.qr-item');
    if(!item) return;

    const src = item.getAttribute('data-src');
    const name = item.getAttribute('data-name') || 'QR';
    if(!src) return;

    const ok = await copyImageByUrl(src, name);
    if(!ok){
      // fallback: open image in new tab
      window.open(src, '_blank');
      showNotification('Browser blocked copy — opened image');
    }
    closeQr();
  });

})();
</script>

{{-- ===== Bonus Season (same logic) ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  const bonusForm = document.getElementById('bonusSeasonForm');
  const bonusStartInput = document.getElementById('bonusStart');
  const bonusEndInput = document.getElementById('bonusEnd');
  const bonusPercentInp = document.getElementById('bonusPercent');
  const bonusMinInp = document.getElementById('bonusMinSpend');
  const bonusClaimDaysInp = document.getElementById('bonusClaimDays');
  const bonusStatus = document.getElementById('bonusStatus');
  const bonusDeactivate = document.getElementById('bonusDeactivateBtn');

  if(!bonusForm) return;

  const fetchUrl = "{{ route('admin.bonus-season.show') }}";
  const storeUrl = "{{ route('admin.bonus-season.store') }}";
  const deactivateUrl = "{{ route('admin.bonus-season.deactivate') }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function loadCurrent(){
    if(bonusStatus) bonusStatus.textContent='Loading...';
    fetch(fetchUrl, { headers:{'X-Requested-With':'XMLHttpRequest'} })
      .then(res=>res.json())
      .then(data=>{
        if(!data.active){
          bonusStartInput.value='';
          bonusEndInput.value='';
          if(bonusPercentInp) bonusPercentInp.value='';
          if(bonusMinInp) bonusMinInp.value='';
          if(bonusClaimDaysInp) bonusClaimDaysInp.value='';
          if(bonusStatus) bonusStatus.textContent = data.label || 'Inactive';
          return;
        }
        bonusStartInput.value = data.start_date || '';
        bonusEndInput.value = data.end_date || '';
        if(bonusPercentInp) bonusPercentInp.value = data.bonus_percent ?? '';
        if(bonusMinInp) bonusMinInp.value = data.min_spend ?? '';
        if(bonusClaimDaysInp) bonusClaimDaysInp.value = data.claim_days ?? '';
        if(bonusStatus) bonusStatus.textContent = data.label || 'Active';
      })
      .catch(()=>{ if(bonusStatus) bonusStatus.textContent='Error loading'; });
  }

  $('#bonusSeasonDropdown').on('click', function(){ setTimeout(loadCurrent, 50); });

  bonusForm.addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(bonusForm);
    fetch(storeUrl, {
      method:'POST',
      headers:{ 'X-CSRF-TOKEN':csrfToken, 'X-Requested-With':'XMLHttpRequest' },
      body: formData
    }).then(res=>res.json()).then(data=>{
      if(data.status==='ok'){
        if(bonusStatus) bonusStatus.textContent = data.message || 'Saved';
        if(window.showNotification) showNotification('Bonus season applied!');
      }else{
        alert('Error saving bonus season.');
      }
    }).catch(()=> alert('Error saving bonus season.'));
  });

  if(bonusDeactivate){
    bonusDeactivate.addEventListener('click', function(){
      if(!confirm('Turn off bonus season?')) return;
      fetch(deactivateUrl, {
        method:'POST',
        headers:{ 'X-CSRF-TOKEN':csrfToken, 'X-Requested-With':'XMLHttpRequest' }
      }).then(res=>res.json()).then(()=>{
        bonusStartInput.value='';
        bonusEndInput.value='';
        if(bonusPercentInp) bonusPercentInp.value='';
        if(bonusMinInp) bonusMinInp.value='';
        if(bonusClaimDaysInp) bonusClaimDaysInp.value='';
        if(bonusStatus) bonusStatus.textContent='Inactive';
        if(window.showNotification) showNotification('Bonus season turned off.');
      }).catch(()=> alert('Error turning off bonus season.'));
    });
  }
});
</script>
@stack('scripts')
</body>
</html>
