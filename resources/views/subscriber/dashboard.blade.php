@extends('layouts.app')
@section('title', 'Welcome')

@section('content')
<div class="page-header">
    <h1>Welcome, {{ auth()->user()->name }}!</h1>
    <span style="font-size:.85rem;color:#888;">
        You are logged in as
        <span class="role-badge role-subscriber">subscriber</span>
    </span>
</div>

<div style="margin-bottom:1.5rem;">
    <p style="color:#666;font-size:.95rem;">
        Browse our latest published articles below.
        You can read articles and leave comments.
    </p>
</div>

@if($articles->isEmpty())
    <div class="card" style="text-align:center;padding:3rem;color:#888;">
        <p>No published articles yet. Check back soon!</p>
    </div>
@else
    <div class="articles-grid">
        @foreach($articles as $article)
        <div class="article-card">
            @if($article->thumbnail)
                <img src="{{ Storage::url($article->thumbnail) }}"
                     alt="{{ $article->title }}">
            @else
                <div style="height:180px;background:#2a2a2a;
                            display:flex;align-items:center;
                            justify-content:center;color:rgba(255,255,255,0.15);
                            font-size:3rem;">&#9632;</div>
            @endif
            <div class="article-card-body">
                @if($article->category)
                    <span style="font-size:.72rem;color:#aaa;display:block;margin-bottom:.4rem;">
                        {{ $article->category->name }}
                    </span>
                @endif
                <h3>{{ $article->title }}</h3>
                <p>{{ Str::limit(strip_tags($article->body), 90) }}</p>
                <a href="{{ route('articles.show', $article->slug) }}">Read more &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
@endif

<div style="margin-top:2rem;display:flex;gap:.75rem;align-items:center;">
    <a href="{{ route('home') }}" class="btn btn-gray">Browse All Articles</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline">Logout</button>
    </form>
</div>
@endsection