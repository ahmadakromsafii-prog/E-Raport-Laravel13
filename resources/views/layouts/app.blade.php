<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b5c50">
    <title>@yield('title','Dashboard') | e-Rapor Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <style>
        :root {
            --brand: #0b6657;
            --brand-dark: #084b43;
            --brand-soft: #e7f4ef;
            --gold: #d7ad55;
            --ink: #183b36;
            --muted: #748780;
            --canvas: #f3f8f5;
            --line: #e5eee9;
            --side-wide: 258px;
            --side-small: 82px
        }

        * {
            box-sizing: border-box
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--canvas);
            color: var(--ink);
            font-size: 14px;
            overflow-x: hidden
        }

        .topbar {
            background: linear-gradient(110deg, #084b43, #0c7561);
            min-height: 74px;
            box-shadow: 0 8px 28px rgba(5, 65, 55, .16);
            position: sticky;
            top: 0;
            z-index: 1030
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: .04em;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 12px
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(145deg, #f6df9c, #c99b3d);
            color: #124d42;
            display: grid;
            place-items: center;
            font-size: 21px;
            box-shadow: 0 5px 14px #032e293d
        }

        .sidebar-toggle {
            width: 40px;
            height: 40px;
            border: 1px solid #ffffff42;
            background: #ffffff16;
            color: white;
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-size: 19px;
            transition: .2s
        }

        .sidebar-toggle:hover {
            background: #ffffff2b;
            transform: translateY(-1px)
        }

        .profile-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff !important;
            background: #ffffff17;
            border: 1px solid #ffffff30;
            border-radius: 999px;
            padding: 6px 12px;
            text-decoration: none
        }

        .profile-toggle:after {
            margin-left: 4px
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #f1d58f;
            color: #174c42;
            font-weight: 800
        }

        .profile-menu {
            border: 1px solid #e6eee9;
            border-radius: 15px;
            box-shadow: 0 16px 40px #123d3020;
            padding: 8px;
            min-width: 220px
        }

        .profile-menu .dropdown-item {
            border-radius: 9px;
            padding: 10px 12px;
            font-weight: 600
        }

        .profile-menu .dropdown-item:hover {
            background: #edf6f0;
            color: var(--brand)
        }

        .app-shell {
            min-height: calc(100vh - 74px)
        }

        .app-row {
            display: flex;
            align-items: flex-start;
            min-height: calc(100vh - 74px)
        }

        .sidebar {
            flex: 0 0 var(--side-wide);
            width: var(--side-wide);
            max-width: var(--side-wide);
            background: linear-gradient(180deg, #084b43 0%, #0b6154 60%, #0a5149 100%);
            padding: 24px 14px;
            color: white;
            position: sticky;
            top: 74px;
            height: calc(100vh - 74px);
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin;
            scrollbar-color: #ffffff55 transparent;
            transition: width .28s ease, flex-basis .28s ease, max-width .28s ease;
            min-height: 0
        }

        .sidebar::-webkit-scrollbar { width: 6px }
        .sidebar::-webkit-scrollbar-thumb { background: #ffffff55; border-radius: 20px }
        .sidebar::-webkit-scrollbar-track { background: transparent }
        .user-avatar { overflow: hidden; flex: 0 0 34px }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block }
        .profile-photo-preview { width: 112px; height: 112px; object-fit: cover; border-radius: 50%; border: 4px solid #e7f4ef; box-shadow: 0 5px 18px #16483e18 }
        .pagination { flex-wrap: wrap; gap: 4px; margin-bottom: 0 }
        .pagination .page-link { border: 1px solid #e2ece6; border-radius: 9px !important; color: var(--brand); font-weight: 700; min-width: 38px; text-align: center; box-shadow: none }
        .pagination .page-item.active .page-link { background: var(--brand); border-color: var(--brand); color: #fff }
        .pagination .page-item.disabled .page-link { color: #a6b5ae; background: #f7faf8 }
        .pagination .page-link:focus { box-shadow: 0 0 0 .2rem #0b66571b }
        .sidebar:after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border: 1px solid #ffffff12;
            border-radius: 50%;
            bottom: -110px;
            left: -90px;
            pointer-events: none
        }

        .sidebar-label {
            font-size: 10px;
            letter-spacing: .16em;
            color: #b6d8cc;
            font-weight: 800;
            padding: 10px 13px;
            margin-top: 8px;
            white-space: nowrap;
            overflow: hidden
        }

        .sidebar a {
            position: relative;
            z-index: 1;
            color: #d7ebe4;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            border-radius: 12px;
            margin: 4px 0;
            font-weight: 600;
            transition: all .22s ease;
            border: 1px solid transparent;
            white-space: nowrap;
            min-height: 46px
        }

        .sidebar a i {
            font-size: 18px;
            width: 22px;
            min-width: 22px;
            text-align: center
        }

        .sidebar a span {
            overflow: hidden;
            text-overflow: ellipsis
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: linear-gradient(100deg, #ffffff25, #ffffff0d);
            color: #fff;
            border-color: #ffffff20;
            transform: translateX(3px)
        }

        .sidebar a.active {
            box-shadow: inset 3px 0 #e4c16f
        }

        main.main-content {
            flex: 1 1 auto;
            min-width: 0;
            width: calc(100% - var(--side-wide));
            padding: 30px clamp(16px, 3vw, 38px);
            max-width: 1800px;
            margin: 0 auto;
            animation: fadeUp .5s ease both
        }

        body.sidebar-collapsed .sidebar {
            flex-basis: var(--side-small);
            width: var(--side-small);
            max-width: var(--side-small);
            padding-left: 12px;
            padding-right: 12px
        }

        body.sidebar-collapsed .sidebar-label {
            opacity: 0;
            height: 10px;
            padding-top: 0;
            padding-bottom: 0
        }

        body.sidebar-collapsed .sidebar a {
            justify-content: center;
            padding: 12px 8px
        }

        body.sidebar-collapsed .sidebar a span {
            display: none
        }

        body.sidebar-collapsed .sidebar a i {
            font-size: 20px
        }

        body.sidebar-collapsed main.main-content {
            width: calc(100% - var(--side-small))
        }

        .page-title {
            font-weight: 800;
            color: #16483e;
            letter-spacing: -.04em
        }

        .text-secondary {
            color: var(--muted) !important
        }

        .stat-card,
        .content-card,
        .card {
            border: 1px solid #e6eee9;
            border-radius: 19px;
            box-shadow: 0 8px 28px rgba(22, 73, 58, .055);
            transition: transform .25s ease, box-shadow .25s ease
        }

        .stat-card {
            padding: 22px;
            background: linear-gradient(145deg, #fff, #fbfdfb);
            height: 100%;
            position: relative;
            overflow: hidden
        }

        .stat-card:after {
            content: "";
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #e7f4ed;
            right: -35px;
            top: -35px
        }

        .stat-card:hover,
        .content-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(22, 73, 58, .10)
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--brand);
            letter-spacing: -.05em
        }

        .content-card {
            background: #fff;
            overflow: hidden
        }

        .content-card .card-body {
            padding: 24px
        }

        .table {
            --bs-table-bg: transparent;
            vertical-align: middle
        }

        .table thead th {
            background: #edf5f0;
            color: #42665c;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 14px 13px
        }

        .table tbody td {
            padding: 14px 13px;
            border-color: #edf2ee
        }

        .table tbody tr {
            transition: background .2s
        }

        .table tbody tr:hover {
            background: #f7fbf8
        }

        .btn {
            border-radius: 11px;
            font-weight: 700;
            transition: all .2s
        }

        .btn-primary {
            --bs-btn-bg: #0b6657;
            --bs-btn-border-color: #0b6657;
            --bs-btn-hover-bg: #084b43;
            --bs-btn-hover-border-color: #084b43;
            box-shadow: 0 5px 13px #0b665729
        }

        .btn-outline-light {
            border-color: #ffffff65
        }

        .form-control,
        .form-select {
            border: 1px solid #dce8e1;
            border-radius: 11px;
            padding: .75rem .9rem
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #64a995;
            box-shadow: 0 0 0 .22rem #0b66571b
        }

        .form-label {
            font-weight: 700;
            color: #34584e
        }

        .alert {
            border: 0;
            border-radius: 13px
        }

        .badge-status {
            font-size: .75rem;
            padding: .45rem .65rem
        }

        .report-sheet {
            background: #fff;
            border: 1px solid #dce5ef;
            padding: 2rem;
            border-radius: .5rem
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(15px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @media(max-width:767.98px) {
            .topbar {
                min-height: 66px
            }

            .brand-mark {
                width: 36px;
                height: 36px
            }

            .app-row {
                display: block
            }

            .sidebar,
            .sidebar-collapsed .sidebar {
                width: 100%;
                max-width: 100%;
                position: relative;
                top: auto;
                height: auto;
                min-height: auto;
                overflow: visible;
                padding: 10px 12px;
                display: flex;
                flex-wrap: wrap;
                gap: 2px
            }

            .sidebar .sidebar-label {
                display: none
            }

            .sidebar a {
                display: inline-flex;
                min-height: 40px;
                padding: 8px 10px;
                margin: 2px;
                font-size: 12px
            }

            .sidebar a span {
                display: inline !important
            }

            .sidebar a i {
                font-size: 16px;
                width: 18px;
                min-width: 18px
            }

            .sidebar-collapsed .sidebar a {
                justify-content: flex-start;
                padding: 8px 10px
            }

            .sidebar-collapsed .sidebar-label {
                display: none
            }

            main.main-content,
            .sidebar-collapsed main.main-content {
                width: 100%;
                padding: 20px 14px
            }

            .profile-toggle {
                padding: 5px 8px
            }

            .profile-toggle .profile-name {
                display: none
            }
        }

        @media print {

            .no-print,
            .topbar,
            .sidebar {
                display: none !important
            }

            body {
                background: #fff
            }

            .report-sheet {
                border: 0;
                padding: 0
            }

            .container-fluid {
                padding: 0 !important
            }
        }

        /* mempertahankan penyempurnaan halaman dari revisi terbaru */
        .page-title {
            font-weight: 800;
            color: #16483e;
            letter-spacing: -.04em
        }

        .text-secondary {
            color: var(--muted) !important
        }

        .stat-card,
        .content-card,
        .card {
            border: 1px solid #e6eee9;
            border-radius: 19px;
            box-shadow: 0 8px 28px rgba(22, 73, 58, .055);
            transition: transform .25s ease, box-shadow .25s ease
        }

        .stat-card {
            padding: 22px;
            background: linear-gradient(145deg, #fff, #fbfdfb);
            height: 100%;
            position: relative;
            overflow: hidden
        }

        .stat-card:after {
            content: "";
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #e7f4ed;
            right: -35px;
            top: -35px
        }

        .stat-card:hover,
        .content-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(22, 73, 58, .10)
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--brand);
            letter-spacing: -.05em
        }

        .content-card {
            background: #fff;
            overflow: hidden
        }

        .content-card .card-body {
            padding: 24px
        }

        .table {
            --bs-table-bg: transparent;
            vertical-align: middle
        }

        .table thead th {
            background: #edf5f0;
            color: #42665c;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 14px 13px
        }

        .table tbody td {
            padding: 14px 13px;
            border-color: #edf2ee
        }

        .table tbody tr {
            transition: background .2s
        }

        .table tbody tr:hover {
            background: #f7fbf8
        }

        .btn {
            border-radius: 11px;
            font-weight: 700;
            transition: all .2s
        }

        .btn-primary {
            --bs-btn-bg: #0b6657;
            --bs-btn-border-color: #0b6657;
            --bs-btn-hover-bg: #084b43;
            --bs-btn-hover-border-color: #084b43;
            box-shadow: 0 5px 13px #0b665729
        }

        .btn-outline-light {
            border-color: #ffffff65
        }

        .form-control,
        .form-select {
            border: 1px solid #dce8e1;
            border-radius: 11px;
            padding: .75rem .9rem
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #64a995;
            box-shadow: 0 0 0 .22rem #0b66571b
        }

        .form-label {
            font-weight: 700;
            color: #34584e
        }

        .alert {
            border: 0;
            border-radius: 13px
        }

        .badge-status {
            font-size: .75rem;
            padding: .45rem .65rem
        }

        .report-sheet {
            background: #fff;
            border: 1px solid #dce5ef;
            padding: 2rem;
            border-radius: .5rem
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-13px)
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        main {
            animation: fadeUp .55s ease both
        }

        @media(max-width:767.98px) {
            .app-shell>.row {
                flex-wrap: wrap
            }

            .sidebar {
                height: auto;
                position: relative;
                top: auto
            }

            .sidebar {
                width: 100%;
                flex: 0 0 100%;
                min-height: auto;
                padding: 12px !important
            }

            .sidebar .sidebar-label {
                display: none
            }

            .sidebar a {
                display: inline-flex;
                padding: 9px 11px;
                margin: 3px;
                font-size: 12px
            }

            .main-content,
            .app-shell.sidebar-collapsed .main-content {
                width: 100%;
                flex: 0 0 100%;
                padding: 20px 14px !important
            }

            .app-shell.sidebar-collapsed .sidebar {
                width: 100%;
                flex-basis: 100%
            }

            .navbar {
                min-height: 66px
            }

            .user-chip {
                font-size: 11px
            }

            .brand-mark {
                width: 36px;
                height: 36px
            }
        }

        @media print {

            .no-print,
            nav,
            .sidebar {
                display: none !important
            }

            body {
                background: #fff
            }

            .report-sheet {
                border: 0;
                padding: 0
            }

            .container-fluid {
                padding: 0 !important
            }
        }

        /* Navigasi mobile: sidebar menjadi drawer agar ruang konten tetap lega. */
        .mobile-sidebar-backdrop{display:none}
        @media(max-width:767.98px){
            .app-row{display:block!important}
            .sidebar,.sidebar-collapsed .sidebar{position:fixed!important;left:0;top:66px;bottom:0;width:min(82vw,310px)!important;max-width:min(82vw,310px)!important;height:calc(100dvh - 66px)!important;min-height:0!important;display:block!important;overflow-y:auto!important;overflow-x:hidden!important;flex:0 0 auto!important;padding:18px 14px!important;transform:translateX(-105%);transition:transform .25s ease;z-index:1050;box-shadow:12px 0 35px #082f2930}
            body.mobile-sidebar-open .sidebar{transform:translateX(0)}
            .sidebar .sidebar-label{display:block!important;opacity:1!important;height:auto!important;padding:10px 13px!important}
            .sidebar a,.sidebar-collapsed .sidebar a{display:flex!important;width:100%;justify-content:flex-start!important;padding:12px 14px!important;margin:4px 0!important;font-size:13px;min-height:46px}
            .sidebar a span,.sidebar-collapsed .sidebar a span{display:inline!important}
            .sidebar a i{font-size:18px!important;width:22px!important;min-width:22px!important}
            .mobile-sidebar-backdrop{position:fixed;inset:66px 0 0;background:#071f1b80;z-index:1040;opacity:0;pointer-events:none;display:block;transition:opacity .2s}
            body.mobile-sidebar-open .mobile-sidebar-backdrop{opacity:1;pointer-events:auto}
            main.main-content,body.sidebar-collapsed main.main-content{width:100%!important;padding:18px 13px!important}
            .topbar{min-height:66px}.navbar-brand{font-size:14px;gap:8px}.brand-mark{width:35px;height:35px}
            .sidebar-toggle{width:38px;height:38px;flex:0 0 38px}
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark topbar">
        <div class="container-fluid px-3 px-lg-4">
            <div class="d-flex align-items-center gap-3"><button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Buka atau kecilkan menu" title="Menu navigasi"><i class="bi bi-grid-1x2-fill"></i></button><a class="navbar-brand" href="{{ route('dashboard') }}"><span class="brand-mark"><i class="bi bi-shield-fill-check"></i></span><span>e-RAPOR <small class="d-block fw-medium" style="font-size:9px;letter-spacing:.16em;color:#c8e3d9">SISTEM INFORMASI SEKOLAH</small></span></a></div>
            <div class="dropdown"><button class="profile-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="user-avatar">@if(auth()->user()->avatar_path)<img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar_path) }}" alt="Foto profil">@else{{ strtoupper(substr(auth()->user()->name,0,1)) }}@endif</span><span class="profile-name text-start"><strong class="d-block" style="font-size:12px">{{ auth()->user()->name }}</strong><small style="color:#c4e2d7">{{ ucfirst(auth()->user()->role) }}</small></span></button>
                <ul class="dropdown-menu dropdown-menu-end profile-menu">
                    <li class="px-3 py-2">
                        <div class="fw-bold">{{ auth()->user()->name }}</div><small class="text-secondary">{{ ucfirst(auth()->user()->role) }}</small>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Profil Saya</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">@csrf<button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar dari akun</button></form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="mobile-sidebar-backdrop" id="mobileSidebarBackdrop"></div>
    <div class="container-fluid app-shell p-0">
        <div class="app-row">
            <aside class="sidebar">
                <div class="sidebar-label w-100">MENU UTAMA</div>
                <a class="{{ request()->routeIs('dashboard')?'active':'' }}" href="{{ route('dashboard') }}"><i class="bi bi-kanban"></i> <span>Dashboard</span></a>
                @if(auth()->user()->role==='guru')
                <a class="{{ request()->routeIs('grades.*')?'active':'' }}" href="{{ route('grades.bulk') }}"><i class="bi bi-journal-check"></i> <span>Input Nilai</span></a>
                <a class="{{ request()->routeIs('report-cards.*')?'active':'' }}" href="{{ route('report-cards.index') }}"><i class="bi bi-clipboard2-check"></i> <span>Olah Nilai Rapor</span></a>
                <a class="{{ request()->routeIs('reports.*')?'active':'' }}" href="{{ route('reports.index') }}"><i class="bi bi-printer"></i> <span>Cetak Rapor</span></a>
                @endif
                @if(in_array(auth()->user()->role,['admin','super_admin'],true))
                <a class="{{ request()->routeIs('grades.*')?'active':'' }}" href="{{ route('grades.bulk') }}"><i class="bi bi-table"></i> <span>Input Nilai</span></a>
                <a class="{{ request()->routeIs('admin.assignments.*')?'active':'' }}" href="{{ route('admin.assignments.index') }}"><i class="bi bi-person-workspace"></i> <span>Penugasan Guru</span></a>
                <a class="{{ request()->routeIs('report-cards.*')?'active':'' }}" href="{{ route('report-cards.index') }}"><i class="bi bi-clipboard2-check"></i> <span>Olah Nilai Semua Kelas</span></a>
                <a class="{{ request()->routeIs('admin.report-cards.*')?'active':'' }}" href="{{ route('admin.report-cards.index') }}"><i class="bi bi-file-earmark-check"></i> <span>Validasi Olah Rapor</span></a>
                <!--<a class="{{ request()->routeIs('admin.biodata')?'active':'' }}" href="{{ route('admin.biodata') }}"><i class="bi bi-person-vcard"></i> <span>Biodata Guru & Siswa</span></a>-->

                @if(auth()->user()->role==='super_admin')
                <a class="{{ request()->routeIs('foundation.*')?'active':'' }}" href="{{ route('foundation.school-admins.index') }}"><i class="bi bi-building-gear"></i> <span>Kelola Kepala Sekolah</span></a>
                @endif
                <div class="sidebar-label mt-3">DATA MASTER</div>
                <a class="{{ request()->is('pengguna*')?'active':'' }}" href="{{ route('admin.master.index','siswa') }}"><i class="bi bi-people"></i> <span>Pengguna</span></a>
                @foreach([['path'=>'kelas','label'=>'Kelas','icon'=>'bi-building'],['path'=>'mata-pelajaran','label'=>'Mata Pelajaran','icon'=>'bi-journal-bookmark'],['path'=>'tahun-ajaran','label'=>'Tahun Ajaran','icon'=>'bi-calendar-range'],['path'=>'semester','label'=>'Semester','icon'=>'bi-calendar2-week']] as $menu)
                <a class="{{ request()->is($menu['path'].'*')?'active':'' }}" href="{{ url('/'.$menu['path']) }}"><i class="bi {{ $menu['icon'] }}"></i> <span>{{ $menu['label'] }}</span></a>
                @endforeach
                <a class="{{ request()->routeIs('reports.*')?'active':'' }}" href="{{ route('reports.index') }}"><i class="bi bi-file-earmark-bar-graph"></i> <span>Data & Cetak Rapor</span></a>
                @endif
                @if(auth()->user()->role==='siswa')<a class="{{ request()->routeIs('reports.*')?'active':'' }}" href="{{ route('reports.index') }}"><i class="bi bi-file-earmark-person"></i> <span>Rapor Saya</span></a>@endif
            </aside>
            <main class="main-content">
                @if(session('success'))<div class="alert alert-success alert-dismissible fade show shadow-sm animate__animated animate__fadeInDown"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                @if($errors->any())<div class="alert alert-danger shadow-sm">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>@endif
                @yield('content')
            </main>
        </div>
    </div>
    <div class="modal fade" id="logoutConfirm" tabindex="-1" aria-labelledby="logoutConfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="rounded-circle d-inline-grid mb-3" style="width:58px;height:58px;place-items:center;background:#fff3e7;color:#c77924;font-size:25px"><i class="bi bi-box-arrow-right"></i></div>
                    <h5 class="fw-bold mb-2" id="logoutConfirmLabel">Keluar dari aplikasi?</h5>
                    <p class="text-secondary mb-4">Apakah Anda yakin ingin mengakhiri sesi sekarang?</p>
                    <div class="d-flex gap-2"><button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Tidak</button><button type="button" class="btn btn-danger flex-fill" id="confirmLogout">Ya, Keluar</button></div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 650,
            once: true,
            offset: 30
        });
        let logoutForm = null;
        document.querySelectorAll('.logout-form').forEach(f => f.addEventListener('submit', e => {
            e.preventDefault();
            logoutForm = f;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('logoutConfirm')).show()
        }));
        document.getElementById('confirmLogout')?.addEventListener('click', () => logoutForm?.submit());
        document.querySelectorAll('.stat-card,.content-card').forEach((el, i) => {
            el.style.animation = `fadeUp .5s ease ${i*.07}s both`
        });
        const toggle = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        const closeMobileMenu = () => document.body.classList.remove('mobile-sidebar-open');
        if (localStorage.getItem('erapor-sidebar-collapsed') === '1' && window.innerWidth > 767) document.body.classList.add('sidebar-collapsed');
        toggle?.addEventListener('click', () => {
            if (window.innerWidth <= 767) document.body.classList.toggle('mobile-sidebar-open');
            else {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('erapor-sidebar-collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
            }
        });
        backdrop?.addEventListener('click', closeMobileMenu);
        document.querySelectorAll('.sidebar a').forEach(link => link.addEventListener('click', closeMobileMenu));
        window.addEventListener('resize', () => { if (window.innerWidth > 767) closeMobileMenu(); });
    </script>
</body>

</html>