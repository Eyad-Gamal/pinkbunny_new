@php
    $currentRoute = request()->route()?->getName() ?? '';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang ?? 'en' }}" dir="{{ $dir ?? 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Pink Bunny Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ===== ADMIN RESET & BASE ===== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #0c0c1d;
            color: #b4b4cc;
            font-size: 14px;
            line-height: 1.6;
            display: flex;
        }

        /* ===== COLOR SYSTEM ===== */
        :root {
            --bg-deep: #0c0c1d;
            --bg-panel: #12122a;
            --bg-card: #181836;
            --bg-elevated: #1e1e42;
            --bg-hover: #22224a;
            --border: rgba(255,255,255,0.06);
            --border-strong: rgba(255,255,255,0.1);
            --text-primary: #eeeef6;
            --text-secondary: #8888a8;
            --text-muted: #5a5a78;
            --accent: #f472b6;
            --accent-glow: rgba(244,114,182,0.15);
            --accent-dark: #db2777;
            --success: #34d399;
            --warning: #fbbf24;
            --danger: #f87171;
            --info: #60a5fa;
        }

        a { color: inherit; text-decoration: none; }
        button { cursor: pointer; font-family: inherit; }
        img { display: block; max-width: 100%; }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: 260px;
            min-width: 260px;
            height: 100vh;
            background: var(--bg-panel);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (max-width: 1023px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
        }

        .sidebar-logo {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .sidebar-logo-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f472b6, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 20px rgba(244,114,182,0.25);
        }
        .sidebar-logo-text h3 { font-size: 15px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
        .sidebar-logo-text span { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 16px 16px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all 0.15s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .nav-link:hover { background: var(--bg-hover); color: var(--text-primary); }
        .nav-link.active {
            background: var(--accent-glow);
            color: var(--accent);
            font-weight: 600;
        }
        .nav-link svg { width: 18px; height: 18px; opacity: 0.7; flex-shrink: 0; }
        .nav-link.active svg { opacity: 1; }
        .nav-link .badge-count {
            margin-left: auto;
            background: var(--accent-dark);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            min-width: 22px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        /* ===== MAIN AREA ===== */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        @media (max-width: 1023px) {
            .admin-main { margin-left: 0; }
        }

        .admin-header {
            height: 64px;
            min-height: 64px;
            background: var(--bg-panel);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-header h1 { font-size: 18px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.3px; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .header-date { font-size: 12px; color: var(--text-muted); }
        .header-avatar {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f472b6, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 8px;
            border-radius: 8px;
        }
        .mobile-toggle:hover { background: var(--bg-hover); }
        @media (max-width: 1023px) { .mobile-toggle { display: flex; } }

        .admin-content {
            flex: 1;
            overflow-y: auto;
            padding: 28px;
        }
        .admin-content::-webkit-scrollbar { width: 6px; }
        .admin-content::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 6px; }

        /* ===== TOAST ===== */
        .toast {
            margin-bottom: 20px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: toastSlide 0.35s ease-out;
        }
        .toast-success { background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.15); color: var(--success); }
        .toast-error { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.15); color: var(--danger); }
        @keyframes toastSlide { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== STATS ===== */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s;
        }
        .stat-card:hover { border-color: var(--border-strong); }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 0 0 0 80px;
            opacity: 0.04;
        }
        .stat-card.pink::after { background: #f472b6; }
        .stat-card.green::after { background: #34d399; }
        .stat-card.blue::after { background: #60a5fa; }
        .stat-card.purple::after { background: #a78bfa; }
        .stat-card.yellow::after { background: #fbbf24; }
        .stat-card.red::after { background: #f87171; }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 14px;
        }
        .stat-icon.pink { background: rgba(244,114,182,0.12); color: #f472b6; }
        .stat-icon.green { background: rgba(52,211,153,0.12); color: #34d399; }
        .stat-icon.blue { background: rgba(96,165,250,0.12); color: #60a5fa; }
        .stat-icon.purple { background: rgba(167,139,250,0.12); color: #a78bfa; }
        .stat-icon.yellow { background: rgba(251,191,36,0.12); color: #fbbf24; }
        .stat-icon.red { background: rgba(248,113,113,0.12); color: #f87171; }

        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--text-primary); margin: 4px 0; letter-spacing: -0.5px; line-height: 1.2; }
        .stat-sub { font-size: 12px; font-weight: 500; }
        .stat-sub.green { color: var(--success); }
        .stat-sub.yellow { color: var(--warning); }
        .stat-sub.blue { color: var(--info); }
        .stat-sub.purple { color: #a78bfa; }

        /* ===== CARDS ===== */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .card-body { padding: 22px; }

        /* ===== GRID LAYOUTS ===== */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .grid-3-1 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        @media (max-width: 900px) { .grid-2, .grid-3-1 { grid-template-columns: 1fr; } }

        /* ===== TABLE ===== */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            background: var(--bg-elevated);
            border-bottom: 1px solid var(--border);
        }
        .data-table td {
            padding: 14px 18px;
            font-size: 13px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .data-table tbody tr { transition: background 0.1s; }
        .data-table tbody tr:hover { background: var(--bg-hover); }
        .data-table tbody tr:last-child td { border-bottom: none; }

        .table-product { display: flex; align-items: center; gap: 12px; }
        .table-product img { width: 38px; height: 38px; border-radius: 8px; object-fit: cover; background: var(--bg-hover); }
        .table-product-placeholder { width: 38px; height: 38px; border-radius: 8px; background: var(--bg-elevated); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--text-muted); }
        .table-product-info strong { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .table-product-info span { font-size: 11px; color: var(--text-muted); }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .badge-success { background: rgba(52,211,153,0.12); color: #34d399; }
        .badge-danger { background: rgba(248,113,113,0.12); color: #f87171; }
        .badge-warning { background: rgba(251,191,36,0.12); color: #fbbf24; }
        .badge-info { background: rgba(96,165,250,0.12); color: #60a5fa; }
        .badge-purple { background: rgba(167,139,250,0.12); color: #a78bfa; }
        .badge-pink { background: rgba(244,114,182,0.12); color: #f472b6; }
        .badge-gray { background: rgba(148,148,168,0.12); color: #9494a8; }
        .badge-indigo { background: rgba(129,140,248,0.12); color: #818cf8; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .btn-primary { background: var(--accent); color: #0c0c1d; }
        .btn-primary:hover { background: var(--accent-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(244,114,182,0.3); }
        .btn-outline { background: transparent; border: 1px solid var(--border-strong); color: var(--text-secondary); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: rgba(248,113,113,0.1); color: var(--danger); border: 1px solid rgba(248,113,113,0.2); }
        .btn-danger:hover { background: var(--danger); color: #fff; }
        .btn-ghost { background: none; border: none; color: var(--text-secondary); padding: 6px 12px; font-size: 12px; }
        .btn-ghost:hover { color: var(--accent); }
        .btn-sm { padding: 6px 14px; font-size: 12px; border-radius: 8px; }

        .link-action { font-size: 12px; font-weight: 500; color: var(--text-muted); transition: color 0.15s; }
        .link-action:hover { color: var(--accent); }
        .link-action.danger:hover { color: var(--danger); }

        /* ===== FORMS ===== */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-deep);
            border: 1px solid var(--border-strong);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 13px;
            font-family: inherit;
            transition: border-color 0.15s;
        }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        .form-input::placeholder { color: var(--text-muted); }
        textarea.form-input { resize: vertical; min-height: 80px; }
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%238888a8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 34px;
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

        .form-check { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: var(--text-secondary); }
        .form-check input[type="checkbox"] { accent-color: var(--accent); width: 16px; height: 16px; }

        .form-error { margin-bottom: 20px; padding: 14px 18px; border-radius: 12px; background: rgba(248,113,113,0.06); border: 1px solid rgba(248,113,113,0.12); }
        .form-error ul { list-style: disc; padding-left: 16px; }
        .form-error li { font-size: 12px; color: var(--danger); margin-bottom: 4px; }

        /* ===== TOOLBAR ===== */
        .toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .toolbar-left { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .toolbar-search { width: 250px; }
        .toolbar-filter { width: 160px; }

        /* ===== TAB PILLS ===== */
        .tab-pills { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 20px; }
        .tab-pill {
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            background: var(--bg-card);
            border: 1px solid var(--border);
            transition: all 0.15s;
        }
        .tab-pill:hover { color: var(--text-primary); border-color: var(--border-strong); }
        .tab-pill.active { background: var(--accent-glow); color: var(--accent); border-color: rgba(244,114,182,0.25); }

        /* ===== STATUS LIST ===== */
        .status-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .status-item:last-child { border-bottom: none; }
        .status-count { font-size: 14px; font-weight: 700; color: var(--text-primary); }

        /* ===== USER AVATAR ===== */
        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f472b6, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        /* ===== PAGINATION ===== */
        .pagination { display: flex; justify-content: center; gap: 4px; margin-top: 20px; }
        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            background: var(--bg-card);
            transition: all 0.15s;
        }
        .pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .pagination .active span { background: var(--accent-glow); border-color: var(--accent); color: var(--accent); font-weight: 700; }
        .pagination .disabled span { opacity: 0.3; cursor: not-allowed; }

        /* ===== TRACKING TIMELINE ===== */
        .timeline-item { display: flex; gap: 14px; padding: 12px 0; }
        .timeline-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); margin-top: 6px; flex-shrink: 0; box-shadow: 0 0 8px var(--accent-glow); }
        .timeline-text { font-size: 13px; color: var(--text-primary); }
        .timeline-meta { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        /* ===== STAR RATING ===== */
        .stars { color: #fbbf24; font-size: 13px; letter-spacing: 1px; }
        .stars-empty { color: var(--text-muted); }

        /* ===== DETAIL GRID ===== */
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border); font-size: 13px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: var(--text-muted); }
        .detail-value { color: var(--text-primary); font-weight: 500; }

        /* ===== SIDEBAR OVERLAY (mobile) ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 99;
            backdrop-filter: blur(4px);
        }
        .sidebar-overlay.active { display: block; }

        /* ===== UTILITIES ===== */
        .text-primary { color: var(--text-primary) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .text-accent { color: var(--accent) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--warning) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'Menlo', 'Monaco', 'Consolas', monospace; }
        .font-bold { font-weight: 700; }
        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: 8px; }
        .mt-2 { margin-top: 16px; }
        .mt-3 { margin-top: 24px; }
        .mb-2 { margin-bottom: 16px; }
        .mb-3 { margin-bottom: 24px; }
        .gap-2 { gap: 16px; }
        .gap-1 { gap: 8px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .flex-wrap { flex-wrap: wrap; }
        .w-full { width: 100%; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 250px; }
        .opacity-50 { opacity: 0.5; }
        .hidden { display: none; }
        .price-sale { color: var(--success); font-size: 12px; margin-left: 4px; }
        .actions-cell { display: flex; align-items: center; gap: 12px; }
        form.inline { display: inline; }
    </style>
</head>
<body>
    {{-- Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">🐰</div>
            <div class="sidebar-logo-text">
                <h3>Pink Bunny</h3>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Main</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.orders') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Orders
                @if(($pendingCount = \App\Models\Order::where('status','pending')->count()) > 0)
                    <span class="badge-count">{{ $pendingCount }}</span>
                @endif
            </a>

            <div class="sidebar-section-label">Catalogue</div>

            <a href="{{ route('admin.products.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.products') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.categories') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>
            <a href="{{ route('admin.brands.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.brands') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                Brands
            </a>

            <div class="sidebar-section-label">Marketing</div>

            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.coupons') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Coupons
            </a>
            <a href="{{ route('admin.slides.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.slides') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Hero Slides
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.reviews') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Reviews
            </a>

            <div class="sidebar-section-label">People</div>

            <a href="{{ route('admin.users.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'admin.users') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Store
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="color: var(--danger);">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="admin-main">
        <header class="admin-header">
            <div class="flex items-center gap-1">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="header-right">
                <span class="header-date">{{ now()->format('D, M d, Y') }}</span>
                <div class="header-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            </div>
        </header>

        <div class="admin-content">
            @if(session('toast'))
                <div class="toast toast-success">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('toast') }}
                </div>
            @endif
            @if(session('error'))
                <div class="toast toast-error">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    </script>
</body>
</html>
