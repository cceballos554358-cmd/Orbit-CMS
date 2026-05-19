@extends('layouts.app')
@section('title', 'Orbit CMS - Home')

@section('content')

<style>
    @@keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @@keyframes floatUp {
        0%, 100% { transform: translateY(0px); }
        50%      { transform: translateY(-10px); }
    }
    @@keyframes orbitSpin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    @@keyframes orbitSpinReverse {
        from { transform: rotate(0deg); }
        to   { transform: rotate(-360deg); }
    }
    @@keyframes twinkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: .2; transform: scale(.6); }
    }
    @@keyframes slideIn {
        from { opacity: 0; transform: translateX(-16px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @@keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.15); }
        50%      { box-shadow: 0 0 0 20px rgba(255,255,255,0); }
    }
    @@keyframes rotateSlow {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    @@keyframes meteorFall {
        0%   { transform: translateX(0) translateY(0); opacity: 1; }
        100% { transform: translateX(-300px) translateY(300px); opacity: 0; }
    }

    .anim-1 { animation: fadeUp .7s ease both; }
    .anim-2 { animation: fadeUp .7s ease .2s both; }
    .anim-3 { animation: fadeUp .7s ease .4s both; }
    .anim-4 { animation: fadeUp .7s ease .6s both; }

    /* ── Space Hero ── */
    .hero {
        position: relative;
        text-align: center;
        padding: 5rem 1rem 4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #0a0a1a 0%, #0d1b2a 50%, #f5f5f5 100%);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 2rem;
        min-height: 520px;
    }

    /* Stars background */
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(1px 1px at 10% 15%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 25% 40%, #fff 0%, transparent 100%),
            radial-gradient(1.5px 1.5px at 40% 10%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 55% 30%, #fff 0%, transparent 100%),
            radial-gradient(2px 2px at 70% 20%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 85% 45%, #fff 0%, transparent 100%),
            radial-gradient(1.5px 1.5px at 15% 60%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 30% 75%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 60% 55%, #fff 0%, transparent 100%),
            radial-gradient(2px 2px at 80% 70%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 5% 85%, #fff 0%, transparent 100%),
            radial-gradient(1.5px 1.5px at 45% 80%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 90% 10%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 35% 20%, #fff 0%, transparent 100%),
            radial-gradient(2px 2px at 65% 85%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 20% 50%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 75% 60%, #fff 0%, transparent 100%),
            radial-gradient(1.5px 1.5px at 50% 45%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 92% 35%, #fff 0%, transparent 100%),
            radial-gradient(1px 1px at 8% 30%, #fff 0%, transparent 100%);
        pointer-events: none;
    }

    /* Meteor streaks */
    .meteor {
        position: absolute;
        width: 2px;
        height: 60px;
        background: linear-gradient(180deg, #fff, transparent);
        transform: rotate(-45deg);
        animation: meteorFall 3s linear infinite;
        opacity: .6;
    }
    .meteor:nth-child(1) { top: 10%; left: 80%; animation-delay: 0s; }
    .meteor:nth-child(2) { top: 20%; left: 60%; animation-delay: 1.2s; }
    .meteor:nth-child(3) { top: 5%;  left: 40%; animation-delay: 2.1s; }

    /* Solar system in background */
    .solar-system {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 500px;
        height: 500px;
        pointer-events: none;
        opacity: .12;
    }
    .sun {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40px;
        height: 40px;
        margin: -20px 0 0 -20px;
        background: radial-gradient(circle, #fff 0%, #ffdd88 50%, transparent 100%);
        border-radius: 50%;
        animation: pulse 3s ease-in-out infinite;
    }
    .orbit-ring-solar {
        position: absolute;
        top: 50%;
        left: 50%;
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: 50%;
        animation: rotateSlow linear infinite;
    }
    .orbit-ring-solar::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: #fff;
        border-radius: 50%;
        top: -4px;
        left: 50%;
        margin-left: -4px;
    }
    .ring-1 {
        width: 120px; height: 120px;
        margin: -60px 0 0 -60px;
        animation-duration: 6s;
    }
    .ring-2 {
        width: 200px; height: 200px;
        margin: -100px 0 0 -100px;
        animation-duration: 10s;
    }
    .ring-3 {
        width: 300px; height: 300px;
        margin: -150px 0 0 -150px;
        animation-duration: 16s;
    }
    .ring-4 {
        width: 420px; height: 420px;
        margin: -210px 0 0 -210px;
        animation-duration: 24s;
    }

    /* Hero logo */
    .hero-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 90px;
        height: 90px;
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 24px;
        margin-bottom: 2rem;
        animation: floatUp 4s ease-in-out infinite;
        position: relative;
        z-index: 2;
        backdrop-filter: blur(10px);
    }
    .hero-logo-inner {
        width: 44px;
        height: 44px;
        border: 3px solid #fff;
        border-radius: 50%;
        position: relative;
    }
    .hero-logo-dot {
        width: 10px;
        height: 10px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        top: -6px;
        left: 50%;
        margin-left: -5px;
        animation: orbitSpin 2s linear infinite;
        transform-origin: 5px 25px;
        box-shadow: 0 0 6px #fff;
    }
    .hero-logo-dot2 {
        width: 6px;
        height: 6px;
        background: rgba(255,255,255,0.6);
        border-radius: 50%;
        position: absolute;
        top: -4px;
        left: 50%;
        margin-left: -3px;
        animation: orbitSpinReverse 3.5s linear infinite;
        transform-origin: 3px 25px;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        margin-bottom: .75rem;
        letter-spacing: -1px;
        text-align: center;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 20px rgba(0,0,0,0.5);
    }
    .hero h1 span {
        background: linear-gradient(90deg, #88ccff, #ffffff, #aaddff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero p {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.75);
        max-width: 500px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
        text-align: center;
        position: relative;
        z-index: 2;
    }
    .hero-actions {
        display: flex;
        gap: .75rem;
        justify-content: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }
    .btn-space {
        background: rgba(255,255,255,0.15);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.4);
        backdrop-filter: blur(10px);
        padding: .7rem 1.5rem;
        font-size: 1rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s, transform .2s;
        display: inline-block;
    }
    .btn-space:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
        color: #fff;
    }
    .btn-space-solid {
        background: #ffffff;
        color: #111;
        border: none;
        padding: .7rem 1.5rem;
        font-size: 1rem;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: background .2s, transform .2s;
        display: inline-block;
    }
    .btn-space-solid:hover {
        background: #e8e8e8;
        transform: translateY(-2px);
    }

    /* ── Features ── */
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 0 0 2rem;
    }
    .feature-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.4rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        transition: transform .25s, box-shadow .25s;
        animation: slideIn .5s ease both;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.11);
    }
    .feature-card:nth-child(1) { animation-delay: .1s; }
    .feature-card:nth-child(2) { animation-delay: .2s; }
    .feature-card:nth-child(3) { animation-delay: .3s; }
    .feature-card:nth-child(4) { animation-delay: .4s; }
    .feature-card:nth-child(5) { animation-delay: .5s; }
    .feature-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: #111;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff;
        margin-bottom: 1rem;
        transition: transform .2s;
    }
    .feature-card:hover .feature-icon { transform: scale(1.1) rotate(-4deg); }
    .feature-card h3 { font-size: .95rem; font-weight: 700; color: #111; margin-bottom: .35rem; }
    .feature-card p  { font-size: .8rem; color: #888; line-height: 1.5; }

    /* ── Stats ── */
    .stats-bar {
        background: #111;
        border-radius: 14px;
        padding: 1.5rem 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin: 0 0 2rem;
        text-align: center;
    }
    .s-num   { font-size: 1.75rem; font-weight: 900; color: #fff; }
    .s-label { font-size: .72rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-top: .2rem; }

    /* ── Section label ── */
    .section-label {
        display: flex; align-items: center; gap: 1rem;
        margin: 0 0 1.25rem;
    }
    .section-label h2 { font-size: 1.25rem; font-weight: 800; color: #111; white-space: nowrap; }
    .section-label::after { content: ''; flex: 1; height: 1px; background: #e8e8e8; }

    /* ── Search ── */
    .search-wrap { display: flex; gap: .75rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .search-wrap input { max-width: 380px; }
    .chips { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 4rem 2rem;
        background: #fff; border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    }
    .empty-icon { font-size: 3rem; margin-bottom: 1rem; display: block; opacity: .3; }
    .empty-state p { color: #888; font-size: .95rem; }

    /* ── Article rows ── */
    .article-row {
        background: #fff; border-radius: 12px; overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        display: flex; align-items: stretch;
        transition: transform .25s, box-shadow .25s;
        margin-bottom: 1rem;
    }
    .article-row:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .article-row-thumb { width: 220px; min-height: 140px; object-fit: cover; flex-shrink: 0; display: block; }
    .article-row-thumb-placeholder {
        width: 220px; min-height: 140px; background: #2a2a2a;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.1); font-size: 2.5rem; flex-shrink: 0;
    }
    .article-row-body {
        padding: 1.25rem 1.5rem; flex: 1;
        display: flex; flex-direction: column; justify-content: space-between;
    }
</style>

{{-- ══════════════════════════════════════════════ --}}
{{-- SPACE HERO                                      --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="hero">
    {{-- Solar system background --}}
    <div class="solar-system">
        <div class="sun"></div>
        <div class="orbit-ring-solar ring-1"></div>
        <div class="orbit-ring-solar ring-2"></div>
        <div class="orbit-ring-solar ring-3"></div>
        <div class="orbit-ring-solar ring-4"></div>
    </div>

    {{-- Meteors --}}
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>

    {{-- Logo --}}
    <div class="hero-logo anim-1">
        <div class="hero-logo-inner">
            <div class="hero-logo-dot"></div>
            <div class="hero-logo-dot2"></div>
        </div>
    </div>

    <h1 class="anim-2">Welcome to <span>Orbit CMS</span></h1>

    <p class="anim-3">
        A modern content platform orbiting around great ideas.
        Write freely, publish instantly, and reach the world.
    </p>

    <div class="hero-actions anim-4">
        @guest
            <a href="{{ route('register') }}" class="btn-space-solid">
                Get Started &rarr;
            </a>
            <a href="{{ route('login') }}" class="btn-space">
                Sign In
            </a>
        @endguest
        @auth
            <a href="{{ route('dashboard') }}" class="btn-space-solid">
                Go to Dashboard &rarr;
            </a>
            @if(!auth()->user()->hasRole('subscriber'))
                <a href="{{ route('author.articles.create') }}" class="btn-space">
                    + Write Article
                </a>
            @endif
        @endauth
    </div>
</div>

{{-- FEATURE CARDS --}}
<div class="features">
    <div class="feature-card">
        <div class="feature-icon">&#9998;</div>
        <h3>Write Freely</h3>
        <p>Authors publish articles instantly with multiple categories and tags.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">&#10003;</div>
        <h3>Editorial Review</h3>
        <p>Editors ensure quality across all published content on the platform.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">&#9632;</div>
        <h3>Role-Based Access</h3>
        <p>Every user gets exactly the tools they need — nothing more, nothing less.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">&#9654;</div>
        <h3>Organised Content</h3>
        <p>Multiple categories and tags keep everything structured and searchable.</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">&#9679;</div>
        <h3>Live Comments</h3>
        <p>Readers engage through comments, replies, images and GIFs.</p>
    </div>
</div>

{{-- STATS BAR --}}
<div class="stats-bar">
    <div>
        <div class="s-num">{{ $stats['published'] }}</div>
        <div class="s-label">Published Articles</div>
    </div>
    <div>
        <div class="s-num">{{ $stats['categories'] }}</div>
        <div class="s-label">Categories</div>
    </div>
    <div>
        <div class="s-num">{{ $stats['users'] }}</div>
        <div class="s-label">Writers &amp; Readers</div>
    </div>
    <div>
        <div class="s-num">{{ $stats['comments'] }}</div>
        <div class="s-label">Comments</div>
    </div>
</div>

{{-- ARTICLES SECTION --}}
<div class="section-label">
    <h2>Latest Articles</h2>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('home') }}" class="search-wrap">
    <input type="text" name="search" class="form-control"
           value="{{ request('search') }}"
           placeholder="Search articles...">
    <button type="submit" class="btn btn-dark">Search</button>
    @if(request('search') || request('category') || request('tag'))
        <a href="{{ route('home') }}" class="btn btn-gray">Clear</a>
    @endif
</form>

{{-- Category chips --}}
<div class="chips">
    <a href="{{ route('home') }}"
       class="btn {{ !request('category') && !request('tag') ? 'btn-dark' : 'btn-gray' }}"
       style="font-size:.8rem;padding:.35rem .85rem;">
       All
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('home', ['category' => $cat->slug]) }}"
           class="btn {{ request('category') == $cat->slug ? 'btn-dark' : 'btn-gray' }}"
           style="font-size:.8rem;padding:.35rem .85rem;">
            {{ $cat->name }}
            <span style="opacity:.5;font-size:.75em;margin-left:.25rem;">
                {{ $cat->articles_list_count }}
            </span>
        </a>
    @endforeach
</div>

{{-- Articles --}}
@if($articles->isEmpty())
    <div class="empty-state">
        <span class="empty-icon">&#9632;</span>
        <p>No articles found. Try a different search or category.</p>
        @if(request('search') || request('category') || request('tag'))
            <a href="{{ route('home') }}" class="btn btn-gray"
               style="margin-top:1rem;">Clear filters</a>
        @endif
    </div>
@else
    <div>
        @foreach($articles as $article)
        <div class="article-row">
            @if($article->thumbnail)
                <img src="{{ Storage::url($article->thumbnail) }}"
                     alt="{{ $article->title }}"
                     class="article-row-thumb">
            @else
                <div class="article-row-thumb-placeholder">&#9632;</div>
            @endif

            <div class="article-row-body">
                <div>
                    <div style="display:flex;align-items:center;
                                gap:.4rem;margin-bottom:.5rem;flex-wrap:wrap;">
                        @foreach($article->categories as $cat)
                            <a href="{{ route('home', ['category' => $cat->slug]) }}"
                               style="font-size:.72rem;background:#f0f0f0;color:#555;
                                      padding:.2rem .6rem;border-radius:20px;
                                      text-decoration:none;transition:background .2s;"
                               onmouseover="this.style.background='#ddd'"
                               onmouseout="this.style.background='#f0f0f0'">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                        @foreach($article->tags as $tag)
                            <a href="{{ route('home', ['tag' => $tag->slug]) }}"
                               style="font-size:.72rem;background:#111;color:#fff;
                                      padding:.2rem .6rem;border-radius:20px;
                                      text-decoration:none;transition:opacity .2s;"
                               onmouseover="this.style.opacity='.7'"
                               onmouseout="this.style.opacity='1'">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>

                    <h3 style="font-size:1.1rem;font-weight:700;color:#111;
                               margin-bottom:.5rem;line-height:1.3;">
                        {{ $article->title }}
                    </h3>
                    <p style="font-size:.85rem;color:#666;line-height:1.6;">
                        {{ Str::limit(strip_tags($article->body), 150) }}
                    </p>
                </div>

                <div style="display:flex;justify-content:space-between;
                            align-items:center;margin-top:.75rem;
                            flex-wrap:wrap;gap:.5rem;">
                    <div style="display:flex;align-items:center;gap:.6rem;">
                        <div style="width:28px;height:28px;background:#111;
                                    border-radius:50%;display:flex;
                                    align-items:center;justify-content:center;
                                    color:#fff;font-size:.72rem;font-weight:700;">
                            {{ strtoupper(substr($article->author->name, 0, 1)) }}
                        </div>
                        <span style="font-size:.78rem;color:#888;">
                            <strong style="color:#555;">
                                {{ $article->author->name }}
                            </strong>
                            &middot;
                            {{ $article->published_at?->diffForHumans() }}
                        </span>
                    </div>
                    <a href="{{ route('articles.show', $article->slug) }}"
                       class="btn btn-dark"
                       style="font-size:.8rem;padding:.35rem .85rem;">
                        Read Article &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:2rem;display:flex;justify-content:center;">
        {{ $articles->links() }}
    </div>
@endif

@endsection