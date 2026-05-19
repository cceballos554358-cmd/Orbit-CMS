@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h1>Admin Dashboard</h1>
    <span style="font-size:.85rem;color:#888;">Welcome back, {{ auth()->user()->name }}</span>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['users'] }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['articles'] }}</div>
        <div class="stat-label">Articles</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['published'] }}</div>
        <div class="stat-label">Published</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['comments'] }}</div>
        <div class="stat-label">Comments</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['categories'] }}</div>
        <div class="stat-label">Categories</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

    {{-- Latest articles --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h2 style="font-size:1rem;font-weight:700;">Latest Articles</h2>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-gray"
               style="font-size:.8rem;padding:.3rem .7rem;">View all</a>
        </div>
        @forelse($latestArticles as $article)
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:.6rem 0;border-bottom:1px solid #f0f0f0;">
            <div>
                <p style="font-size:.875rem;font-weight:600;">
                    {{ Str::limit($article->title, 35) }}
                </p>
                <p style="font-size:.75rem;color:#999;">{{ $article->author->name }}</p>
            </div>
            <span class="badge badge-{{ $article->status }}">{{ $article->status }}</span>
        </div>
        @empty
        <p style="color:#aaa;font-size:.875rem;">No articles yet.</p>
        @endforelse
    </div>

    {{-- Latest users --}}
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h2 style="font-size:1rem;font-weight:700;">Latest Users</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-gray"
               style="font-size:.8rem;padding:.3rem .7rem;">View all</a>
        </div>
        @forelse($latestUsers as $user)
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:.6rem 0;border-bottom:1px solid #f0f0f0;">
            <div>
                <p style="font-size:.875rem;font-weight:600;">{{ $user->name }}</p>
                <p style="font-size:.75rem;color:#999;">{{ $user->email }}</p>
            </div>
            <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
        </div>
        @empty
        <p style="color:#aaa;font-size:.875rem;">No users yet.</p>
        @endforelse
    </div>

</div>
@endsection

