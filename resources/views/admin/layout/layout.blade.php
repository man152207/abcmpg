@php
use App\Models\UserPrivilege;
use Carbon\Carbon;
use App\Models\Ad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

$today = Carbon::today();

/** ====== TODAY SUMMARY ====== */
$totalNPR = Ad::whereDate('created_at', $today)
              ->selectRaw('SUM(COALESCE(CAST(REPLACE(NRP, ",", "") AS DECIMAL(18,2)),0)) AS totalNPR')
              ->value('totalNPR');
$formattedTotalNPR = number_format((float)$totalNPR, 2, '.', ',');

$totalUSD = Ad::whereDate('created_at', $today)->sum('USD');
$formattedTotalUSD = number_format((float)$totalUSD, 2, '.', ',');

/** ====== USER / PRIVILEGE / RECEPTION FLAGS ====== */
$adminUser = auth('admin')->user();

$_privRow = UserPrivilege::select('full_or_partial','option')
            ->where('user_id', $adminUser?->id)->first();

$isSuperAdmin   = (bool)($_privRow->full_or_partial ?? 0);
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

$canSeeReception = $isSuperAdmin || $inReception; // Reception menu कसलाई देखाउने?
$isReceptionOnly = $inReception && !$isSuperAdmin; // Reception मात्र भएकालाई minimal UI
@endphp

<!DOCTYPE html>
<html lang="en" class="mpg-layout">
<head class="mpg-layout">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title class="mpg-layout">@yield('title', 'MPG Solution | Admin Dashboard')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Slick Carousel CSS (may not be needed now, but keeping in case other pages use it) -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.9.0/slick/slick.css"/>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Font Awesome 6 (for some newer icons you used) -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-2w1oGr3qM2n0G3t6T6q2r9..."
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />

    <style>
        /* --- Global resets for modern look --- */
        .mpg-layout html { font-size:95%; scroll-behavior:smooth; }
        .mpg-layout body {
            font-family:'Source Sans Pro',sans-serif;
            background:#f7fafc;
            color:#2d3748;
            line-height:1.4;
            margin:0;
        }

        /* --- Sidebar --- */
        .mpg-layout .main-sidebar{
            background:linear-gradient(180deg,#093b7b 0%,#646564 100%);
            box-shadow:3px 0 10px rgba(0,0,0,.1);
            transition:transform .3s;
        }
        .mpg-layout .brand-link{
            background:linear-gradient(90deg,#093b7b 0%,#646564 100%);
            color:#fff;
            font-size:1.25rem;
            font-weight:600;
            padding:1.2rem;
            display:flex;
            align-items:center;
            border-bottom:1px solid rgba(255,255,255,.15);
        }
        .mpg-layout .brand-image{
            height:36px;width:36px;margin-right:.75rem;opacity:1;
        }

        .mpg-layout .nav-sidebar .nav-link{
            color:#fff;
            font-size:1rem;
            font-weight:600;
            padding:4px;
            margin:.3rem .5rem;
            border-radius:6px;
            display:flex;
            align-items:center;
            transition:all .3s;
        }
        .mpg-layout .nav-sidebar .nav-link:hover{
            background:rgba(255,126,95,.2);
            color:#feb47b;
            border-left:4px solid #feb47b;
            transform:scale(1.02);
        }
        .mpg-layout .nav-sidebar .nav-link.active{
            background:#ff7e5f;
            color:#fff;
            box-shadow:0 3px 6px rgba(0,0,0,.15);
            border-left:4px solid #feb47b;
        }
        .mpg-layout .nav-sidebar .nav-icon{
            color:#feb47b;
            margin-right:1rem;
            font-size:1.1rem;
            transition:transform .3s;
        }
        .mpg-layout .nav-sidebar .nav-link:hover .nav-icon{
            transform:rotate(10deg);
        }
        .mpg-layout .nav-sidebar .nav-treeview .nav-link{
            font-size:.9rem;
            font-weight:400;
            padding:5px;
            margin:.2rem .5rem;
            border-radius:6px;
        }
        .mpg-layout .nav-sidebar .nav-treeview .nav-link:hover{
            background:rgba(255,255,255,.1);
            color:#feb47b;
        }
        .mpg-layout .nav-sidebar .badge{
            background:#feb47b;
            color:#2d3748;
            font-size:.75rem;
            padding:.3rem .5rem;
            border-radius:12px;
        }
        .mpg-layout .custom-divider{
            border:0;
            height:1px;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);
            margin:1.5rem 0;
        }

        /* --- Navbar --- */
        .mpg-layout .main-header.navbar{
            background:#fff;
            border-bottom:1px solid rgba(0,0,0,.05);
            box-shadow:0 2px 8px rgba(0,0,0,.1);
            padding:.6rem 1.5rem;
            position:sticky;
            top:0;
            z-index:1000;
            transition:all .3s;
        }
        .mpg-layout .navbar-nav{
            padding:0 1.2rem;
            display:flex;
            align-items:center;
        }
        .mpg-layout .navbar-nav .nav-item .nav-link{
            color:#2d3748;
            font-size:.9rem;
            font-weight:500;
            padding:.6rem 1rem;
            border-radius:4px;
            transition:all .3s;
        }
        .mpg-layout .navbar-nav .nav-item .nav-link:hover{
            color:#ff7e5f;
            background:rgba(255,126,95,.1);
        }

        .mpg-layout .acbtn .btn-info{
            background:#093b7b;
            color:#fff;
            font-size:.85rem;
            padding:.5rem 1rem;
            margin:0 .3rem;
            border-radius:6px;
            border:none;
            transition:all .3s;
        }
        .mpg-layout .acbtn .btn-info:hover{
            background:#ff7e5f;
            transform:translateY(-2px);
            box-shadow:0 2px 6px rgba(0,0,0,.15);
        }

        .mpg-layout .button-container{
            display:flex;
            align-items:center;
            margin-right:1.5rem;
        }
        .mpg-layout .premium-button{
            background:#ff7e5f;
            color:#fff;
            font-size:.85rem;
            padding:.5rem 1rem;
            border-radius:6px;
            border:none;
            margin:0 .3rem;
            cursor:pointer;
            transition:all .3s;
        }
        .mpg-layout .premium-button:hover{
            background:#feb47b;
            transform:translateY(-2px);
            box-shadow:0 2px 6px rgba(0,0,0,.15);
        }

        .mpg-layout .hiddenContent{display:none;}

        .mpg-layout .user-profile{
            background:#646564;
            color:#fff;
            border-radius:6px;
            display:flex;
            align-items:center;
            margin-right:1rem;
            transition:all .3s;
            padding:4px 15px;
        }
        .mpg-layout .user-profile:hover{
            background:#ff7e5f;
        }
        .mpg-layout .user-profile img{
            width:28px;
            height:28px;
            border-radius:50%;
            margin-right:.6rem;
        }
        .mpg-layout .user-profile a{
            color:#fff;
            font-size:.9rem;
            font-weight:500;
        }

        .mpg-layout .dark-mode-toggle{
            cursor:pointer;
            font-size:1.1rem;
            color:#2d3748;
            padding:.5rem;
            transition:all .3s;
        }
        .mpg-layout .dark-mode-toggle:hover{
            color:#ff7e5f;
        }

        .mpg-layout body.dark-mode{
            background:#1a202c;
            color:#e2e8f0;
        }
        .mpg-layout body.dark-mode .main-header.navbar{
            background:#2d3748;
            border-bottom:1px solid rgba(255,255,255,.1);
            box-shadow:0 2px 8px rgba(0,0,0,.2);
        }
        .mpg-layout body.dark-mode .navbar-nav .nav-item .nav-link{
            color:#e2e8f0;
        }
        .mpg-layout body.dark-mode .navbar-nav .nav-item .nav-link:hover{
            color:#feb47b;
            background:rgba(255,126,95,.2);
        }
        .mpg-layout body.dark-mode .content-wrapper{
            background:#1a202c;
        }
        .mpg-layout body.dark-mode .acbtn .btn-info{
            background:#ff7e5f;
        }
        .mpg-layout body.dark-mode .acbtn .btn-info:hover{
            background:#feb47b;
        }
        .mpg-layout body.dark-mode .user-profile{
            background:#4a5568;
        }
        .mpg-layout body.dark-mode .user-profile:hover{
            background:#ff7e5f;
        }

        .mpg-layout .content-wrapper{
            background:#f7fafc;
            padding:0;
        }
        .mpg-layout .main-footer{
            background:#fff;
            color:#2d3748;
            border-top:1px solid rgba(0,0,0,.05);
            padding:1rem;
            text-align:center;
        }
        .mpg-layout .main-footer a{
            color:#ff7e5f;
            transition:color .3s;
        }
        .mpg-layout .main-footer a:hover{
            color:#feb47b;
        }

        @media (max-width:767.98px){
          .mpg-layout .main-sidebar{transform:translateX(-100%);}
          .mpg-layout .sidebar-open .main-sidebar{transform:translateX(0);}

          .mpg-layout .nav-sidebar .nav-link{
              font-size:.95rem;
              padding:.8rem 1.2rem;
          }
          .mpg-layout .nav-sidebar .nav-treeview .nav-link{
              font-size:.85rem;
              padding-left:2.5rem;
          }

          .mpg-layout .navbar-nav{
              padding:.5rem;
          }
          .mpg-layout .button-container{
              flex-wrap:wrap;
              margin-right:.5rem;
          }
          .mpg-layout .premium-button{
              margin:.2rem;
          }
        }

        .mpg-layout .wxbox{
            margin-left:12px;
            font-size:14px;
            font-weight:700;
            border-radius:6px;
            background:#343a40;
            border:1px solid #2d3a63;
            color:#fff;
            padding:6px 14px;
            display:inline-flex;
            align-items:center;
            gap:6px;
        }
        .mpg-layout .wxbox #wxIcon{
            font-size:16px;
        }
    
        /***** QR MODAL UI (FINAL MERGED) *****/
        .mpg-layout #qrOverlay{
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.5);
            backdrop-filter:blur(4px) saturate(140%);
            -webkit-backdrop-filter:blur(4px) saturate(140%);
            display:none; /* hidden initially */
            z-index:2500;
            align-items:center;
            justify-content:center;
            padding:16px;
        }
        
        /* modal card (popup container) */
        .mpg-layout #qrModal{
            background:#ffffff;
            color:#1e293b;
            width:100%;
            max-width:860px;               /* bigger than 480px */
            border-radius:20px;            /* softer corners */
            box-shadow:0 24px 48px rgba(0,0,0,.4);
            border:1px solid rgba(0,0,0,.06);
            overflow:hidden;
            display:flex;
            flex-direction:column;
            max-height:80vh;
            padding-bottom:8px;            /* small breathing room at bottom */
        }
        
        /* header bar */
        .mpg-layout #qrModalHead{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            padding:12px 16px;
            background:linear-gradient(135deg,#093b7b 0%,#646564 100%);
            color:#fff;
        }
        .mpg-layout #qrModalTitle{
            display:flex;
            flex-direction:column;
            font-size:.8rem;
            line-height:1.3;
        }
        .mpg-layout #qrModalTitle .main{
            font-size:.9rem;
            font-weight:600;
            color:#fff;
        }
        .mpg-layout #qrModalTitle .sub{
            font-size:.7rem;
            color:rgba(255,255,255,.7);
        }
        
        .mpg-layout #qrCloseBtn{
            background:rgba(255,255,255,.12);
            color:#fff;
            border:0;
            border-radius:8px;
            padding:6px 10px;
            font-size:.8rem;
            font-weight:600;
            line-height:1;
            cursor:pointer;
            transition:.15s;
        }
        .mpg-layout #qrCloseBtn:hover{
            background:rgba(255,255,255,.22);
        }
        
        /* body */
        .mpg-layout #qrModalBody{
            padding:16px;
            overflow-y:auto;
        }
        
        /* grid of QR cards */
        .mpg-layout .qr-grid{
            display:grid;
            grid-template-columns:repeat(auto-fill, minmax(170px,1fr));  /* wider cards */
            gap:16px;                                                    /* more gap */
        }
        
        /* individual QR card */
        .mpg-layout .qr-item{
            background:#fff;
            border-radius:14px;                          /* was 12px */
            border:1px solid #e2e8f0;
            box-shadow:0 14px 28px rgba(15,23,42,.10);   /* a bit deeper */
            padding:16px 14px;                           /* was 12px 10px */
            text-align:center;
            cursor:pointer;
            transition:.16s all;
            min-width:170px;                             /* NEW: ensure card looks roomy */
            min-height:200px;                            /* NEW: taller card feel */
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:flex-start;
        }
        .mpg-layout .qr-item:hover{
            transform:translateY(-2px) scale(1.02);
            border-color:#ff7e5f;
            box-shadow:0 20px 32px rgba(15,23,42,.16);
        }
        
        /* QR image inside card */
        .mpg-layout .qr-item img{
            width:100%;
            max-width:210px;      /* you asked bigger, keep 210px */
            max-height:210px;
            border-radius:10px;   /* slightly rounder */
            border:1px solid #cbd5e1;
            background:#fff;
            object-fit:contain;
            background-color:#fff;
        }
        
        /* label under QR */
        .mpg-layout .qr-label{
            font-size:.8rem;
            font-weight:600;
            color:#1e293b;
            margin-top:10px;      /* was 8px */
            word-break:break-word;
            text-align:center;
            line-height:1.3;
        }
        
        /* little helper text under grid */
        .mpg-layout .qr-hint{
            text-align:center;
            font-size:.7rem;
            color:#64748b;
            margin-top:8px;
            line-height:1.4;
        }
        
        /* ===================== */
        /* Dark mode overrides   */
        /* ===================== */
        .mpg-layout body.dark-mode #qrModal{
            background:#1e2535;
            color:#f8fafc;
            border-color:rgba(255,255,255,.08);
            box-shadow:0 24px 48px rgba(0,0,0,.8);
        }
        .mpg-layout body.dark-mode #qrModalHead{
            background:linear-gradient(135deg,#1f2937 0%,#4b5563 100%);
        }
        .mpg-layout body.dark-mode #qrModalTitle .main{
            color:#fff;
        }
        .mpg-layout body.dark-mode #qrModalTitle .sub{
            color:rgba(255,255,255,.6);
        }
        .mpg-layout body.dark-mode .qr-item{
            background:#2a3246;
            border:1px solid rgba(255,255,255,.08);
            box-shadow:0 10px 20px rgba(0,0,0,.8);
        }
        .mpg-layout body.dark-mode .qr-item:hover{
            border-color:#ff7e5f;
            box-shadow:0 16px 28px rgba(0,0,0,.9);
        }
        .mpg-layout body.dark-mode .qr-label{
            color:#f8fafc;
        }
        .mpg-layout body.dark-mode .qr-hint{
            color:#94a3b8;
        }

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed mpg-layout">
<div class="wrapper mpg-layout">

    {{-- ========== TOP NAVBAR ========== --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light mpg-layout">
        <ul class="navbar-nav mpg-layout">
            <li class="nav-item mpg-layout">
                <a class="nav-link mpg-layout" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars mpg-layout"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block mpg-layout">
                <a href="{{ $isReceptionOnly ? route('recp.dashboard') : route('admin.dashboard') }}"
                   class="nav-link mpg-layout">Home</a>
            </li>
        </ul>

        @if(!$isReceptionOnly)
            <div class="acbtn mpg-layout">
                <span class="btn btn-info mpg-layout">Today: ${{ number_format($totalUSD, 2, '.', ',') }}</span>
                <span class="btn btn-info mpg-layout">Today: Rs.{{ number_format($totalNPR, 2, '.', ',') }}</span>
                <a href="{{ url('/admin/dashboard/ads/summary') }}" class="mpg-layout">
                    <button class="btn btn-info mpg-layout">All Summary</button>
                </a>

                <div class="btn btn-dark mpg-layout wxbox">
                    <span id="wxIcon">⛅</span>
                    <strong id="wxCity">Pokhara</strong> •
                    <strong id="wxTemp">--°C</strong> •
                    <strong id="wxTime">--:--</strong>
                </div>
            </div>
        @endif

        <ul class="navbar-nav ml-auto mpg-layout">

            @if(!$isReceptionOnly)
                {{-- QR Button (opens modal) --}}
                <li class="nav-item mpg-layout">
                    <a href="#" id="qrToggleBtn"
                       style="display:inline-flex;align-items:center;gap:8px;
                              padding:.5rem .75rem;border:1px solid #ddd;
                              border-radius:10px;background:#fff;
                              cursor:pointer;text-decoration:none;color:inherit;">
                        <i class="fa-solid fa-qrcode"></i>
                        QR
                    </a>
                </li>

                {{-- Boosting Queue --}}
                <li class="{{ request()->routeIs('boosting.*') ? 'active' : '' }}">
                    <a href="{{ route('boosting.index') }}"
                       style="display:inline-flex;align-items:center;gap:8px;
                              padding:.5rem .75rem;border:1px solid #ddd;
                              border-radius:10px;background:#fff;
                              cursor:pointer;text-decoration:none;color:inherit;">
                        <i class="fa-solid fa-list-check"></i>
                        Boosting Queue
                    </a>
                </li>

                {{-- Prompts --}}
                <li class="{{ request()->routeIs('admin.prompts.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.prompts.index') }}"
                       style="display:inline-flex;align-items:center;gap:8px;
                              padding:.5rem .75rem;border:1px solid #ddd;
                              border-radius:10px;background:#fff;
                              cursor:pointer;text-decoration:none;color:inherit;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        Prompts
                    </a>
                </li>

                {{-- Bank Dropdown --}}
                <li class="nav-item dropdown mpg-layout">
                    <a class="nav-link mpg-layout" data-toggle="dropdown" href="#">
                        <i class="fas fa-university mpg-layout"></i> Banks
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mpg-layout">
                        <a href="#" class="dropdown-item mpg-layout" onclick="CopyBank('hiddenContentNic', 'GBL PAC')">GBL PAC</a>
                        <a href="#" class="dropdown-item mpg-layout" onclick="CopyBank('hiddenContentGBL', 'GBL BAC')">GBL BAC</a>
                        <a href="#" class="dropdown-item mpg-layout" onclick="CopyBank('hiddenContentAdbl', 'ADBL BAC')">ADBL BAC</a>
                        <a href="#" class="dropdown-item mpg-layout" onclick="CopyBank('hiddenContentBank1', 'SiDrth')">SiDrth</a>
                    </div>
                </li>

                {{-- 2FA --}}
                <li class="nav-item mpg-layout">
                    <a href="{{ route('admin.2fa.index') }}" class="nav-link mpg-layout">
                        <i class="nav-icon fas fa-shield-alt mpg-layout" style=" font-size: 20px; "></i>
                    </a>
                </li>

                {{-- Hidden bank contents for copy --}}
                <div id="hiddenContentNic" class="hiddenContent mpg-layout">
                    <p class="mpg-layout">Bank Details: </p>
                    <br class="mpg-layout">A/C Holder Name: MAN PRASAD GURUNG</br>
                    <br class="mpg-layout">Account Number: 06507010002936</br>
                    <br class="mpg-layout">Bank Name: GLOBAL IME BANK LTD.</br>
                </div>
                <div id="hiddenContentGBL" class="hiddenContent mpg-layout">
                    <p class="mpg-layout">Bank Details: </p>
                    <br class="mpg-layout">A/C Holder Name: MPG SOLUTION PRIVATE LIMITED</br>
                    <br class="mpg-layout">Account Number: 06501010005708</br>
                    <br class="mpg-layout">Bank Name: GLOBAL IME BANK LTD.</br>
                </div>
                <div id="hiddenContentAdbl" class="hiddenContent mpg-layout">
                    <p class="mpg-layout">Bank Details: </p>
                    <br class="mpg-layout">A/C Holder Name: MPG SOLUTION PVT LTD</br>
                    <br class="mpg-layout">Account Number: 0329005385010012</br>
                    <br class="mpg-layout">Bank Name: AGRICULTURAL DEVELOPMENT BANK</br>
                    <br class="mpg-layout">Bank Branch: Chauthe Branch</br>
                </div>
                <div id="hiddenContentBank1" class="hiddenContent mpg-layout">
                    <p class="mpg-layout">Bank Details: </p>
                    <br class="mpg-layout">A/C Holder Name: PASCHIM POKHARA MEDIA PRIVATE LIMITED</br>
                    <br class="mpg-layout">Account Number: 00515148144</br>
                    <br class="mpg-layout">Bank Name: Siddhartha Bank Limited</br>
                    <br class="mpg-layout">Bank Branch: BAGAR</br>
                </div>
            @endif

            {{-- User Dropdown (always visible) --}}
            @php
                $avatar = $adminUser && $adminUser->profile_picture
                    ? (Str::startsWith($adminUser->profile_picture, ['http://','https://'])
                        ? $adminUser->profile_picture
                        : asset('storage/'.$adminUser->profile_picture))
                    : asset('dist/img/user2-160x160.jpg');
            @endphp
            <li class="nav-item dropdown mpg-layout">
                <a class="nav-link mpg-layout" data-toggle="dropdown" href="#">
                    <img src="{{ $avatar }}" class="img-circle elevation-2 mpg-layout"
                         alt="User Image"
                         style="width:28px;height:28px;object-fit:cover;">
                    <span class="d-none d-md-inline mpg-layout">{{ $adminUser?->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right mpg-layout">
                    <a href="{{route('admin.profile.edit')}}" class="dropdown-item mpg-layout">
                        <i class="fas fa-user mpg-layout"></i> Profile
                    </a>

                    @if(!$isReceptionOnly && $isSuperAdmin)
                        <a href="{{route('admin.user.add')}}" class="dropdown-item mpg-layout">
                            <i class="fas fa-user-plus mpg-layout"></i> Add User
                        </a>
                        <a href="{{route('admin.user.show')}}" class="dropdown-item mpg-layout">
                            <i class="fas fa-users mpg-layout"></i> List Users
                        </a>
                    @endif

                    <div class="dropdown-divider mpg-layout"></div>
                    <a href="{{route('admin.logout')}}" class="dropdown-item mpg-layout">
                        <i class="fas fa-sign-out-alt mpg-layout"></i> Logout
                    </a>
                </div>
            </li>

            {{-- Dark mode (always visible) --}}
            <li class="nav-item mpg-layout">
                <i class="fas fa-moon dark-mode-toggle mpg-layout" onclick="toggleDarkMode()"></i>
            </li>
        </ul>
    </nav>

    {{-- ========= QR MODAL (hidden until QR button clicked) ========= --}}
    @if(!$isReceptionOnly)
    <div id="qrOverlay" class="mpg-layout">
        <div id="qrModal" class="mpg-layout">
            <div id="qrModalHead" class="mpg-layout">
                <div id="qrModalTitle" class="mpg-layout">
                    <span class="main mpg-layout">Scan / Copy QR</span>
                    <span class="sub mpg-layout">Tap any QR to copy. It will auto-close.</span>
                </div>
                <button id="qrCloseBtn" class="mpg-layout">Close ✖</button>
            </div>

            <div id="qrModalBody" class="mpg-layout">
                <div class="qr-grid mpg-layout">
                    @foreach(File::glob(public_path('images').'/*') as $image)
                        @php
                            $rel = str_replace(public_path(), '', $image);
                            $name = pathinfo($image, PATHINFO_FILENAME);
                        @endphp
                        <div class="qr-item mpg-layout" data-img="{{ $rel }}" data-name="{{ $name }}">
                            <img src="{{ $rel }}"
                                 alt="{{ $name }}"
                                 class="qr-img mpg-layout">
                            <div class="qr-label mpg-layout">{{ $name }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="qr-hint mpg-layout">
                    Long-press / tap to copy image.<br>
                    Works for direct paste into chat, WhatsApp, etc.
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========== SIDEBAR ========== --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4 mpg-layout">
        <a href="{{ $isReceptionOnly ? route('recp.dashboard') : '/admin/dashboard/ads_list' }}"
           class="brand-link mpg-layout">
            <img src="{{asset('dist/img/Brand-icon2.png')}}"
                 alt="AdminLTE Logo"
                 class="brand-image img-circle elevation-3 mpg-layout">
            <span class="brand-text font-weight-light mpg-layout" style="font-weight: 600;">MPG Solution</span>
        </a>

        <div class="sidebar mpg-layout">
            <nav class="mt-2 mpg-layout">
                <ul class="nav nav-pills nav-sidebar flex-column mpg-layout"
                    data-widget="treeview" role="menu" data-accordion="false">

                    {{-- A. Dashboard --}}
                    <li class="nav-item mpg-layout">
                        <a href="{{ $isReceptionOnly ? route('recp.dashboard') : route('admin.dashboard') }}"
                           class="nav-link mpg-layout {{ request()->routeIs('admin.dashboard') || request()->routeIs('recp.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt mpg-layout"></i>
                            <p class="mpg-layout">Dashboard</p>
                        </a>
                    </li>

                    {{-- B. Reception / Admissions --}}
                    @if($canSeeReception)
                        @php $recpActive = request()->is('admin/recp*') || request()->routeIs('recp.*'); @endphp
                        <li class="nav-item has-treeview mpg-layout {{ $recpActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link mpg-layout {{ $recpActive ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bell mpg-layout"></i>
                                <p class="mpg-layout">Reception<i class="fas fa-angle-left right mpg-layout"></i></p>
                            </a>
                            <ul class="nav nav-treeview mpg-layout">
                                <li class="nav-item mpg-layout">
                                    <a href="{{ route('recp.dashboard') }}"
                                       class="nav-link mpg-layout {{ request()->routeIs('recp.dashboard') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">Overview</p>
                                    </a>
                                </li>
                                <li class="nav-item mpg-layout">
                                    <a href="{{ route('recp.students.list') }}"
                                       class="nav-link mpg-layout {{ request()->routeIs('recp.students.list') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">Students</p>
                                    </a>
                                </li>
                                <li class="nav-item mpg-layout">
                                    <a href="{{ route('recp.students.create') }}"
                                       class="nav-link mpg-layout {{ request()->routeIs('recp.students.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">Add Student</p>
                                    </a>
                                </li>
                                <li class="nav-item mpg-layout">
                                    <a href="#" class="nav-link mpg-layout" onclick="return recpStudentEditPrompt();">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">Edit Student</p>
                                    </a>
                                </li>
                                <li class="nav-item mpg-layout">
                                    <a href="#" class="nav-link mpg-layout" onclick="return recpEnrollPrompt();">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">Enroll Student</p>
                                    </a>
                                </li>
                                <li class="nav-item mpg-layout">
                                    <a href="#" class="nav-link mpg-layout" onclick="return recpPaymentPrompt();">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">Take Payment</p>
                                    </a>
                                </li>
                                <li class="nav-item mpg-layout">
                                    <a href="#" class="nav-link mpg-layout" onclick="return recpDocPrompt();">
                                        <i class="far fa-circle nav-icon mpg-layout"></i>
                                        <p class="mpg-layout">New Document</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    {{-- Reception-only भन्दा तलका मेनुहरू लुकाउने --}}
                    @if(!$isReceptionOnly)

                        {{-- C. Sales & CRM --}}
                        @if(in_array(3, $userPrivileges))
                            <li class="nav-header mpg-layout">Sales & CRM</li>

                            <li class="nav-item mpg-layout">
                                <a href="{{ route('admin.followups.index') }}"
                                   class="nav-link mpg-layout {{ request()->routeIs('admin.followups.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-address-book mpg-layout"></i>
                                    <p class="mpg-layout">Follow-Ups</p>
                                </a>
                            </li>

                            <li class="nav-item mpg-layout">
                                <a href="{{ route('customer.show') }}" class="nav-link mpg-layout">
                                    <i class="nav-icon fas fa-users mpg-layout"></i>
                                    <p class="mpg-layout">Customers</p>
                                </a>
                            </li>

                            <li class="nav-item has-treeview mpg-layout">
                                <a href="#" class="nav-link mpg-layout">
                                    <i class="nav-icon fas fa-concierge-bell mpg-layout"></i>
                                    <p class="mpg-layout">
                                        Quotation
                                        <i class="fas fa-angle-left right mpg-layout"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview mpg-layout">
                                    <li class="nav-item mpg-layout">
                                        <a href="{{ route('quotation.generate') }}" class="nav-link mpg-layout">
                                            <i class="far fa-circle nav-icon mpg-layout"></i>
                                            <p class="mpg-layout">Quotation Generator</p>
                                        </a>
                                    </li>
                                    <li class="nav-item mpg-layout">
                                        <a href="{{ route('item.show') }}" class="nav-link mpg-layout">
                                            <i class="far fa-circle nav-icon mpg-layout"></i>
                                            <p class="mpg-layout">Service Management</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item mpg-layout">
                                <a href="{{ url('/ad-management') }}"
                                   class="nav-link mpg-layout {{ request()->is('ad-management*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users mpg-layout"></i>
                                    <p class="mpg-layout">AdAccounts</p>
                                </a>
                            </li>
                        @endif

                        {{-- D. Billing & Finance --}}
                        @if(in_array(6, $userPrivileges) || in_array(7, $userPrivileges) || in_array(4, $userPrivileges))
                            <li class="nav-header mpg-layout">Billing & Finance</li>

                            @if(in_array(6, $userPrivileges))
                                <li class="nav-item has-treeview mpg-layout">
                                    <a href="#" class="nav-link mpg-layout">
                                        <i class="nav-icon fas fa-file-invoice mpg-layout"></i>
                                        <p class="mpg-layout">
                                            Invoice
                                            <i class="fas fa-angle-left right mpg-layout"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview mpg-layout">
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('invoice.pendingBills') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Requires Bill</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('invoice.add') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">New Invoice</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('invoice.list') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Invoice List</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if(in_array(7, $userPrivileges))
                                <li class="nav-item has-treeview mpg-layout">
                                    <a href="#" class="nav-link mpg-layout">
                                        <i class="nav-icon fas fa-money-bill mpg-layout"></i>
                                        <p class="mpg-layout">
                                            Accounts
                                            <i class="fas fa-angle-left right mpg-layout"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview mpg-layout">
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('card.show') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Manage</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('all_in_one') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">All Details</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('credit.show') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Credit Detail</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                                            <a href="{{ route('credit.summary') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Credit Summary</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('debit.show') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Debit Detail</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                                            <a href="{{ route('debit.summary') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Debit Summary</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if(in_array(4, $userPrivileges))
                                <li class="nav-item has-treeview mpg-layout">
                                    <a href="#" class="nav-link mpg-layout">
                                        <i class="nav-icon fas fa-rupee-sign mpg-layout"></i>
                                        <p class="mpg-layout">
                                            Expenditures
                                            <i class="fas fa-angle-left right mpg-layout"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview mpg-layout">
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('client.add') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">New Purchase</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('client.show') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Purchased Details</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('exp.show') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Other Expenses</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                                            <a href="{{ route('client_summary') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Expenses Summary</p>
                                            </a>
                                        </li>
                                        <li class="nav-item mpg-layout">
                                            <a href="{{ route('other_income.index') }}" class="nav-link mpg-layout">
                                                <i class="far fa-circle nav-icon mpg-layout"></i>
                                                <p class="mpg-layout">Other Income</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endif

                        {{-- E. Operations & Records --}}
                        @if(in_array(2, $userPrivileges))
                            <li class="nav-header mpg-layout">Operations & Records</li>

                            <li class="nav-item has-treeview mpg-layout">
                                <a href="#" class="nav-link mpg-layout">
                                    <i class="nav-icon fa fa-book mpg-layout"></i>
                                    <p class="mpg-layout">
                                        Record Book
                                        <i class="fas fa-angle-left right mpg-layout"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview mpg-layout">
                                    <li class="nav-item mpg-layout">
                                        <a href="{{ route('ads.show') }}" class="nav-link mpg-layout">
                                            <i class="far fa-circle nav-icon mpg-layout"></i>
                                            <p class="mpg-layout">Daily Records</p>
                                        </a>
                                    </li>
                                        <li class="nav-item mpg-layout">
                                        <a href="https://app.mpg.com.np/duty-schedule"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="nav-link mpg-layout {{ request()->is('duty-schedule*') ? 'active' : '' }}">
                                            <i class="fa fa-calendar-check nav-icon mpg-layout"></i>
                                            <p class="mpg-layout">Duty Schedule</p>
                                        </a>
                                    </li>
                                    <li class="nav-item mpg-layout">
                                        <a href="{{ route('ads_complete.show') }}" class="nav-link mpg-layout">
                                            <i class="far fa-circle nav-icon mpg-layout"></i>
                                            <p class="mpg-layout">Previous Records</p>
                                        </a>
                                    </li>
                                    <li class="nav-item mpg-layout" style="display: {{ $isSuperAdmin ? 'block' : 'none' }};">
                                        <a href="{{ route('ads.summary') }}" class="nav-link mpg-layout">
                                            <i class="far fa-circle nav-icon mpg-layout"></i>
                                            <p class="mpg-layout">Monthly Summary</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item mpg-layout">
                                <a href="{{ route('admin.daily-logs.index') }}"
                                   class="nav-link mpg-layout {{ request()->routeIs('admin.daily-logs.*') ? 'active' : '' }}">
                                    <i class="fa fa-book nav-icon mpg-layout"></i>
                                    <p class="mpg-layout">Daily Log Book</p>
                                </a>
                            </li>
                        @endif

                        {{-- F. Content & Assets --}}
                        <li class="nav-header mpg-layout">Content & Assets</li>

                        <li class="nav-item mpg-layout">
                            <a href="{{ route('admin.multimedia.index') }}"
                               class="nav-link mpg-layout {{ request()->routeIs('admin.multimedia.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-photo-video mpg-layout"></i>
                                <p class="mpg-layout">Multimedia</p>
                            </a>
                        </li>

                        <li class="nav-item mpg-layout">
                            <a href="{{ url('/admin/packages') }}"
                               class="nav-link mpg-layout {{ request()->is('admin/packages*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-boxes mpg-layout"></i>
                                <p class="mpg-layout">Packages</p>
                            </a>
                        </li>

                        {{-- G. Communication --}}
                        @if(in_array(3, $userPrivileges))
                            <li class="nav-header mpg-layout">Communication</li>
                            <li class="nav-item mpg-layout">
                                <a href="{{ route('admin.chat.internal') }}" class="nav-link mpg-layout">
                                    <i class="nav-icon fas fa-comments mpg-layout"></i>
                                    <p class="mpg-layout">Chat</p>
                                </a>
                            </li>
                        @endif

                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    {{-- ========== CONTENT ========== --}}
    <div class="content-wrapper mpg-layout">
        <div class="content-header mpg-layout" style="padding: 0px;margin-top: -7px;">
            <div class="container-fluid mpg-layout">
                <div class="row mb-2 mpg-layout">
                    <div class="col-sm-6 mpg-layout"></div>
                </div>
            </div>
        </div>
        @yield('content')
    </div>

    {{-- ========== RIGHT SIDEBAR (unused) ========== --}}
    <aside class="control-sidebar control-sidebar-dark mpg-layout"></aside>

    {{-- ========== FOOTER ========== --}}
    <footer class="main-footer mpg-layout">
        <strong class="mpg-layout">Copyright © 2017-{{ date('Y') }}
            <a href="http://bagaicharesort.com" class="mpg-layout">MPG Solution</a>.
        </strong>
        All rights reserved.
    </footer>

</div> {{-- .wrapper --}}

{{-- ========= CORE SCRIPTS ========= --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>

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
  /* ========= CLOCK (wxTime) updates every 1s ========= */
  const wxTime=document.getElementById('wxTime');
  function tick(){
    if(!wxTime) return;
    wxTime.textContent=new Intl.DateTimeFormat('en-GB',{
      hour:'2-digit',minute:'2-digit',second:'2-digit',
      hour12:false,timeZone:'Asia/Kathmandu'
    }).format(new Date());
  }
  tick();
  setInterval(tick,1000);

  /* ========= WEATHER fetch ========= */
  const url=`{{ route('api.weather') }}?city=Pokhara,NP`;
  fetch(url).then(r=>r.json()).then(d=>{
    const cityEl=document.getElementById('wxCity');
    const tempEl=document.getElementById('wxTemp');
    const iconEl=document.getElementById('wxIcon');

    if(cityEl) cityEl.textContent = d.city || 'Pokhara';
    if(tempEl) tempEl.textContent =
        (d.temp!=null) ? (parseInt(d.temp,10)+'°C') : '--°C';

    let condition = (d.condition || '').toLowerCase();
    let icon = "⛅";
    if(condition.includes("clear")) icon="☀️";
    else if(condition.includes("cloud")) icon="☁️";
    else if(condition.includes("rain")) icon="🌧️";
    else if(condition.includes("thunder")) icon="⛈️";
    else if(condition.includes("snow")) icon="❄️";
    else if(condition.includes("wind")) icon="🌬️";
    else if(condition.includes("fog")||condition.includes("mist")) icon="🌫️";
    if(iconEl) iconEl.textContent = icon;
  }).catch(()=>{});

  /* ========= DARK MODE ========= */
  window.toggleDarkMode = function(){
    document.body.classList.toggle('dark-mode');
    const icon = document.querySelector('.dark-mode-toggle');
    if(icon){
      icon.classList.toggle('fa-moon');
      icon.classList.toggle('fa-sun');
    }
  };

  /* ========= Toast Notification (used by copyImageToClipboard and CopyBank) ========= */
  window.showNotification = function(message){
    const notification = document.createElement('div');
    notification.innerText = message;
    notification.style.position = 'fixed';
    notification.style.bottom = '40px';
    notification.style.right = '20px';
    notification.style.backgroundColor = '#38a169';
    notification.style.color = '#ffffff';
    notification.style.padding = '12px 24px';
    notification.style.borderRadius = '6px';
    notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    notification.style.zIndex = '10000';
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.remove();
    }, 2500);
  };

  /* ========= Copy bank details text ========= */
  window.CopyBank = function(contentId, bankName){
    var content = document.getElementById(contentId).innerText;
    var tempTextArea = document.createElement('textarea');
    tempTextArea.value = content;
    document.body.appendChild(tempTextArea);
    tempTextArea.select();
    document.execCommand('copy');
    document.body.removeChild(tempTextArea);
    showNotification(`Copied: ${bankName}`);
  };

  /* ========= Copy QR Image to Clipboard ========= */
  async function copyImageToClipboard(imgElement) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = imgElement.naturalWidth;
    canvas.height = imgElement.naturalHeight;

    ctx.drawImage(imgElement, 0, 0);

    canvas.toBlob(async function(blob) {
      try {
        await navigator.clipboard.write([
          new ClipboardItem({ 'image/png': blob })
        ]);
        showNotification(`Copied: ${imgElement.alt || 'Image'}`);
      } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy image to clipboard.');
      }
    }, 'image/png');
  }

  /* ========= QR MODAL OPEN/CLOSE ========= */
  const qrToggleBtn = document.getElementById('qrToggleBtn'); // navbar "QR" button
  const qrOverlay   = document.getElementById('qrOverlay');   // backdrop
  const qrCloseBtn  = document.getElementById('qrCloseBtn');  // "Close ✖"

  function openQrModal(){
    if(!qrOverlay) return;
    qrOverlay.style.display = 'flex';
  }
  function closeQrModal(){
    if(!qrOverlay) return;
    qrOverlay.style.display = 'none';
  }

  if(qrToggleBtn){
    qrToggleBtn.addEventListener('click', function(e){
      e.preventDefault();
      if(!qrOverlay) return;
      if(qrOverlay.style.display === 'flex'){
        closeQrModal();
      } else {
        openQrModal();
      }
    });
  }

  if(qrCloseBtn){
    qrCloseBtn.addEventListener('click', function(){
      closeQrModal();
    });
  }

  // click outside (on the overlay background) also closes
  if(qrOverlay){
    qrOverlay.addEventListener('click', function(e){
      if(e.target === qrOverlay){
        closeQrModal();
      }
    });
  }

  // tap QR item: copy + close
  const qrItems = document.querySelectorAll('.qr-item');
  qrItems.forEach(function(item){
    item.addEventListener('click', async function(){
      const imgEl = item.querySelector('img');
      if(imgEl){
        await copyImageToClipboard(imgEl);
      }
      closeQrModal();
    });
  });

})();
</script>

</body>
</html>
