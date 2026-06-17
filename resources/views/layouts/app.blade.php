<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lab Tekkim UAD')</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        [x-cloak] { display: none !important; }
        /* ===== SIDEBAR LAYOUT SYSTEM ===== */
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --color-sidebar: #1a1d29;
            --color-sidebar-hover: #2d3148;
            --color-sidebar-active: #0d6efd;
            --color-bg: #f0f2f5;
            --color-primary: #0d6efd;
            --color-primary-hover: #0b5ed7;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--color-bg);
            overflow-x: hidden;
        }

        /* Sidebar */
        .app-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--color-sidebar);
            z-index: 40;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            min-height: var(--topbar-height);
        }

        .sidebar-brand img { width: 36px; height: 36px; object-fit: contain; }

        .sidebar-brand-text {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.3;
        }

        .sidebar-brand-text small {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }

        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        .sidebar-label {
            padding: 12px 20px 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255,255,255,0.35);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            margin: 1px 0;
        }

        .sidebar-link:hover {
            background: var(--color-sidebar-hover);
            color: #fff;
        }

        .sidebar-link.active {
            background: rgba(13,110,253,0.15);
            color: #fff;
            border-left-color: var(--color-sidebar-active);
        }

        .sidebar-link svg { width: 20px; height: 20px; flex-shrink: 0; opacity: 0.7; }
        .sidebar-link.active svg, .sidebar-link:hover svg { opacity: 1; }

        .sidebar-badge {
            margin-left: auto;
            background: var(--color-primary);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 12px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer form button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 0;
            color: rgba(255,255,255,0.55);
            background: none;
            border: none;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
        }

        .sidebar-footer form button:hover { color: #ff6b6b; }

        /* Topbar */
        .app-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 30;
            transition: left 0.3s ease;
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; }

        .topbar-title { font-size: 17px; font-weight: 600; color: #1f2937; }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .topbar-user:hover { background: #f3f4f6; }

        .topbar-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--color-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .topbar-user-info { line-height: 1.3; }
        .topbar-user-name { font-size: 13px; font-weight: 600; color: #1f2937; }
        .topbar-user-role { font-size: 11px; color: #6b7280; }

        .btn-sidebar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border: none;
            background: #f3f4f6;
            border-radius: 8px;
            cursor: pointer;
            color: #374151;
        }

        .btn-sidebar-toggle:hover { background: #e5e7eb; }

        /* Main Content */
        .app-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-wrapper { padding: 24px; }

        /* Cards */
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h2, .card-header h3 { font-size: 16px; font-weight: 600; color: #1f2937; }
        .card-body { padding: 20px; }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: box-shadow 0.2s;
        }

        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg { width: 24px; height: 24px; }
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.yellow { background: #fef3c7; color: #d97706; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        .stat-icon.red { background: #fee2e2; color: #dc2626; }

        .stat-info p { font-size: 13px; color: #6b7280; margin-bottom: 4px; }
        .stat-info h3 { font-size: 24px; font-weight: 700; color: #1f2937; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary { background: var(--color-primary); color: #fff; }
        .btn-primary:hover { background: var(--color-primary-hover); }
        .btn-success { background: #059669; color: #fff; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-warning:hover { background: #b45309; }
        .btn-secondary { background: #6b7280; color: #fff; }
        .btn-secondary:hover { background: #4b5563; }
        .btn-outline { background: transparent; border: 1px solid #d1d5db; color: #374151; }
        .btn-outline:hover { background: #f9fafb; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* Tables */
        .table-wrapper { overflow-x: auto; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead { background: #f9fafb; }

        .data-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table td {
            padding: 12px 16px;
            font-size: 13.5px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .data-table tbody tr:hover { background: #f9fafb; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f3f4f6; color: #374151; }
        .badge-primary { background: #dbeafe; color: #1d4ed8; }

        /* Sidebar Overlay (mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 35;
        }

        /* Responsive */
        .hidden-mobile { display: block; }
        .show-mobile { display: none; }

        @media (max-width: 1024px) {
            .app-sidebar { transform: translateX(-100%); }
            .app-sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .app-topbar { left: 0; padding: 0 12px; gap: 8px; }
            .topbar-title { font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
            .app-content { margin-left: 0; }
            .btn-sidebar-toggle { display: flex; }
            .hidden-mobile { display: none !important; }
            .show-mobile { display: block !important; }
        }

        @media (max-width: 640px) {
            .content-wrapper { padding: 16px; }
            .topbar-user-info { display: none; }
            .topbar-title { max-width: 120px; }
        }

        /* Forms */
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500; color: #374151; }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13.5px;
            color: #1f2937;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        }

        /* Alerts */
        .alert-box {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-box.success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-box.danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-box.warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .alert-box.info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

        /* Welcome card */
        .welcome-card {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border-radius: 12px;
            padding: 24px;
            color: #fff;
            margin-bottom: 24px;
        }

        .welcome-card h2 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .welcome-card p { font-size: 14px; opacity: 0.85; }

        /* Role Switcher in topbar */
        .role-switcher-wrap { position: relative; }
        .role-switcher-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .role-switcher-btn:hover { background: #e0e7ff; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Topbar --}}
    @include('layouts.partials.topbar')

    {{-- Main Content --}}
    <div class="app-content">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert-box success">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-box danger">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box danger">
                    <div style="flex-direction: column; align-items: flex-start;">
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 600; margin-bottom: 4px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Terdapat kesalahan input:
                        </div>
                        <ul style="margin-left: 28px; list-style-type: disc;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.app-sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>