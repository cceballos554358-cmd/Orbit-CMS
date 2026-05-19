<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orbit CMS')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html {
            background-color: #f5f5f5; 
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #111111;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }
        .navbar-brand {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: .5px;
            display: flex;
            align-items: center;
            gap: .6rem;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .navbar-brand:hover { color: #cccccc; }

        .orbit-icon-wrap {
            width: 30px; height: 30px;
            background: #ffffff;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .orbit-circle {
            width: 14px; height: 14px;
            border: 2.5px solid #111;
            border-radius: 50%;
            position: relative;
        }
        .orbit-dot {
            width: 5px; height: 5px;
            background: #111;
            border-radius: 50%;
            position: absolute;
            top: -4px; left: 50%;
            margin-left: -2.5px;
            animation: orbitSpin 2s linear infinite;
            transform-origin: 2.5px 10px;
        }
        @keyframes orbitSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: .15rem;
            list-style: none;
        }
        .navbar-links a {
            color: #aaaaaa;
            text-decoration: none;
            padding: .35rem .7rem;
            border-radius: 6px;
            font-size: .82rem;
            transition: background .2s, color .2s;
            white-space: nowrap;
            display: block;
        }
        .navbar-links a:hover { background: #2a2a2a; color: #ffffff; }
        .navbar-links a.active {
            background: #ffffff;
            color: #111111;
            font-weight: 600;
        }

        /* ── Dropdown ── */
        .nav-dropdown { position: relative; }
        .nav-dropdown-trigger {
            color: #aaaaaa;
            padding: .35rem .7rem;
            border-radius: 6px;
            font-size: .82rem;
            cursor: pointer;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: .3rem;
            transition: background .2s, color .2s;
            user-select: none;
        }
        .nav-dropdown-trigger:hover,
        .nav-dropdown-trigger.open {
            background: #2a2a2a;
            color: #fff;
        }
        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #1a1a1a;
            border-radius: 10px;
            min-width: 170px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            padding: .4rem 0;
            z-index: 2000;
            border: 1px solid #2a2a2a;
        }
        .nav-dropdown-menu.open { display: block; }
        .nav-dropdown-menu a {
            display: block;
            padding: .5rem 1rem;
            color: #aaa;
            text-decoration: none;
            font-size: .82rem;
            transition: background .2s, color .2s;
        }
        .nav-dropdown-menu a:hover { background: #2a2a2a; color: #fff; }
        .nav-dropdown-menu a.active { color: #fff; font-weight: 600; }
        .dropdown-arrow {
            font-size: .6rem;
            transition: transform .2s;
            display: inline-block;
        }
        .dropdown-arrow.open { transform: rotate(180deg); }

        /* ── Role badge ── */
        .role-badge {
            font-size: .65rem;
            padding: .2rem .5rem;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .role-admin       { background:#ffffff; color:#111; }
        .role-editor      { background:#555; color:#fff; }
        .role-author      { background:#777; color:#fff; }
        .role-contributor { background:#999; color:#fff; }
        .role-subscriber  { background:#bbb; color:#111; }

        /* ── Logout button — always visible ── */
        .logout-btn {
            background: transparent;
            border: 1.5px solid #444;
            color: #aaa;
            padding: .3rem .75rem;
            border-radius: 6px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
            white-space: nowrap;
        }
        .logout-btn:hover {
            background: #ffffff;
            color: #111111;
            border-color: #ffffff;
        }

        /* ── Flash messages ── */
        .flash {
            padding: .75rem 1.5rem;
            font-size: .875rem;
            font-weight: 500;
            text-align: center;
        }
        .flash-success { background:#d4edda; color:#155724; }
        .flash-error   { background:#f8d7da; color:#721c24; }

        /* ── Main content ── */
        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── Cards ── */
        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            transition: box-shadow .25s, transform .25s;
        }
        .card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-block;
            padding: .5rem 1.1rem;
            border-radius: 7px;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn:active { transform: translateY(0); }
        .btn-dark         { background:#111111; color:#fff; }
        .btn-dark:hover   { background:#333333; }
        .btn-gray         { background:#e0e0e0; color:#111; }
        .btn-gray:hover   { background:#cccccc; }
        .btn-danger       { background:#c0392b; color:#fff; }
        .btn-danger:hover { background:#a93226; }
        .btn-outline      { background:transparent; border:1.5px solid #111; color:#111; }
        .btn-outline:hover { background:#111; color:#fff; }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        th {
            background:#111111; color:#ffffff;
            padding:.75rem 1rem; text-align:left; font-weight:600;
        }
        td {
            padding:.7rem 1rem;
            border-bottom:1px solid #e8e8e8;
            vertical-align:middle;
        }
        tr:hover td { background:#f9f9f9; }
        tr:last-child td { border-bottom:none; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: #444;
            margin-bottom: .35rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .form-control {
            width: 100%;
            padding: .6rem .9rem;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: .9rem;
            background: #fafafa;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-control:focus {
            border-color: #111;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.07);
        }
        textarea.form-control { resize: vertical; min-height: 160px; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #111; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .badge-published { background:#d5f0e0; color:#1a6e3c; }
        .badge-pending   { background:#fef3cd; color:#856404; }
        .badge-draft     { background:#e8e8e8; color:#555; }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
            transition: transform .25s, box-shadow .25s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .stat-card .stat-number { font-size: 2rem; font-weight: 800; color: #111; }
        .stat-card .stat-label {
            font-size: .75rem; color: #888;
            text-transform: uppercase; letter-spacing: .5px; margin-top: .25rem;
        }

        /* ── Article grid ── */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .article-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            transition: transform .25s, box-shadow .25s;
        }
        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.13);
        }
        .article-card img { width:100%; height:180px; object-fit:cover; display:block; }
        .article-card-body { padding: 1rem 1.25rem 1.25rem; }
        .article-card-body h3 {
            font-size: 1rem; font-weight: 700;
            margin-bottom: .4rem; color: #111;
        }
        .article-card-body p { font-size: .82rem; color: #666; margin-bottom: .75rem; }
        .article-card-body a {
            font-size: .8rem; font-weight: 600;
            color: #111; text-decoration: underline;
        }

        /* ── Footer ── */
        footer {
            background: #111;
            color: #666;
            text-align: center;
            padding: 1rem;
            font-size: .8rem;
            margin-top: auto;
        }
        /* ── Pagination ── */
        nav[role="navigation"] {
            margin-top: 1.5rem; font-size: .85rem; color: #666;
        }
        nav[role="navigation"] svg {
            width: 1.15rem; height: 1.15rem; display: inline-block; vertical-align: middle;
        }
        nav[role="navigation"] > div:first-child { display: none; }
        nav[role="navigation"] > div:last-child {
            display: flex; align-items: center; justify-content: space-between; 
            flex-wrap: wrap; gap: 1rem; border-top: 1px solid #eee; padding-top: 1rem;
        }
        nav[role="navigation"] p { margin: 0; }
        
        .relative.inline-flex {
            display: inline-flex; align-items: center; padding: .35rem .65rem; margin: 0 .1rem;
            border: 1px solid #e0e0e0; border-radius: 6px; background: #fff;
            color: #333; text-decoration: none; transition: .2s; font-weight: 500;
        }
        a.relative.inline-flex:hover { 
            background: #f5f5f5; border-color: #ccc; 
        }
        
        span[aria-current="page"] > span.relative {
            background: #111; color: #fff; border-color: #111; cursor: default;
        }
        
        span[aria-disabled="true"] > span.relative {
            background: #fafafa; color: #bbb; cursor: not-allowed; border-color: #eee;
        }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <div class="orbit-icon-wrap">
            <div class="orbit-circle">
                <div class="orbit-dot"></div>
            </div>
        </div>
        Orbit CMS
    </a>

    <ul class="navbar-links">
        <li>
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'active' : '' }}">
               Home
            </a>
        </li>

        @guest
            <li>
                <a href="{{ route('login') }}"
                   class="{{ request()->routeIs('login') ? 'active' : '' }}">
                   Login
                </a>
            </li>
            <li>
                <a href="{{ route('register') }}"
                   class="{{ request()->routeIs('register') ? 'active' : '' }}">
                   Register
                </a>
            </li>
        @endguest

        @auth
            <li>
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                   Dashboard
                </a>
            </li>

            @if(auth()->user()->hasRole(['admin','editor','author','contributor']))
            <li>
                <a href="
                    @if(auth()->user()->isAdmin())
                        {{ route('admin.articles.index') }}
                    @elseif(auth()->user()->isEditor())
                        {{ route('editor.articles.index') }}
                    @else
                        {{ route('author.articles.index') }}
                    @endif"
                   class="{{ request()->routeIs('*articles*') ? 'active' : '' }}">
                   Articles
                </a>
            </li>
            @endif

            @if(auth()->user()->isAdmin())
            <li class="nav-dropdown">
                <div class="nav-dropdown-trigger" id="manageBtn"
                     onclick="toggleDropdown()">
                    Manage
                    <span class="dropdown-arrow" id="dropArrow">&#9660;</span>
                </div>
                <div class="nav-dropdown-menu" id="manageMenu">
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users*') ? 'active':'' }}">
                       Users
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="{{ request()->routeIs('admin.categories*') ? 'active':'' }}">
                       Categories
                    </a>
                    <a href="{{ route('admin.comments.index') }}"
                       class="{{ request()->routeIs('admin.comments*') ? 'active':'' }}">
                       Comments
                       @php
                           $reportedCount = \App\Models\Comment::where('is_reported', true)->count();
                       @endphp
                       @if($reportedCount > 0)
                           <span style="background:#c0392b;color:#fff;
                                        border-radius:20px;padding:.1rem .4rem;
                                        font-size:.65rem;font-weight:700;
                                        margin-left:.3rem;">
                               {{ $reportedCount }}
                           </span>
                       @endif
                    </a>
                </div>
            </li>
            @endif

            <li>
                <a href="{{ route('profile.edit') }}"
                   class="{{ request()->routeIs('profile*') ? 'active' : '' }}">
                   Profile
                </a>
            </li>

            <li>
                <span class="role-badge role-{{ auth()->user()->role }}">
                    {{ auth()->user()->role }}
                </span>
            </li>

            {{-- LOGOUT — always visible, styled distinctly --}}
            <li>
                <form method="POST" action="{{ route('logout') }}"
                      style="display:inline">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</nav>

{{-- ── FLASH MESSAGES ── --}}
@if(session('success'))
    <div class="flash flash-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-error">{{ session('error') }}</div>
@endif

{{-- ── CONTENT ── --}}
<main class="main-content">
    @yield('content')
</main>

<footer>
    &copy; {{ date('Y') }} Orbit CMS. All rights reserved.
</footer>

<script>
function toggleDropdown() {
    const menu  = document.getElementById('manageMenu');
    const btn   = document.getElementById('manageBtn');
    const arrow = document.getElementById('dropArrow');
    if (!menu) return;
    const isOpen = menu.classList.contains('open');
    menu.classList.toggle('open', !isOpen);
    btn.classList.toggle('open',  !isOpen);
    arrow.classList.toggle('open', !isOpen);
}

document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.nav-dropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.getElementById('manageMenu')
            ?.classList.remove('open');
        document.getElementById('manageBtn')
            ?.classList.remove('open');
        document.getElementById('dropArrow')
            ?.classList.remove('open');
    }
});
</script>

</body>
</html>