@extends('layouts.app')
@section('title', 'Editor Dashboard')

@section('content')
<div class="page-header">
    <h1>Editor Dashboard</h1>
    <span style="font-size:.85rem;color:#888;">Welcome, {{ auth()->user()->name }}</span>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $pending->count() }}</div>
        <div class="stat-label">Pending Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $published }}</div>
        <div class="stat-label">Published</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $comments }}</div>
        <div class="stat-label">Awaiting Comments</div>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h2 style="font-size:1rem;font-weight:700;">Articles Pending Review</h2>
        <a href="{{ route('editor.articles.index') }}" class="btn btn-gray"
           style="font-size:.8rem;padding:.3rem .7rem;">View All</a>
    </div>

    @forelse($pending as $article)
    <div style="display:flex;justify-content:space-between;align-items:center;
                padding:.75rem 0;border-bottom:1px solid #f0f0f0;">
        <div>
            <p style="font-weight:600;font-size:.9rem;">{{ $article->title }}</p>
            <p style="font-size:.78rem;color:#999;">
                By {{ $article->author->name }}
                &middot; {{ $article->created_at->diffForHumans() }}
            </p>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('articles.show', $article->slug) }}"
               class="btn btn-gray" style="font-size:.8rem;padding:.3rem .65rem;">Read</a>
            <form method="POST" action="{{ route('editor.articles.status', $article) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="published">
                <button class="btn btn-dark" style="font-size:.8rem;padding:.3rem .65rem;">
                    Publish
                </button>
            </form>
            <form method="POST" action="{{ route('editor.articles.status', $article) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="draft">
                <button class="btn btn-danger" style="font-size:.8rem;padding:.3rem .65rem;">
                    Reject
                </button>
            </form>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:2rem;color:#aaa;">
        <p>No articles pending review.</p>
    </div>
    @endforelse
</div>

<div style="margin-top:1rem;display:flex;gap:.75rem;">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline">Logout</button>
    </form>
</div>
@endsection