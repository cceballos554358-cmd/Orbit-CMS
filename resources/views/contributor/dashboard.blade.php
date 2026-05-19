@extends('layouts.app')
@section('title', 'Contributor Dashboard')

@section('content')
<div class="page-header">
    <h1>My Contributions</h1>
    <a href="{{ route('contributor.drafts.create') }}" class="btn btn-dark">
        + New Draft
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['draft'] }}</div>
        <div class="stat-label">Drafts</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['published'] }}</div>
        <div class="stat-label">Published</div>
    </div>
</div>

<div class="card">
    <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">
        All My Contributions
    </h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drafts as $article)
                <tr>
                    <td><strong>{{ Str::limit($article->title, 40) }}</strong></td>
                    <td style="color:#888;font-size:.8rem;">
                        {{ $article->category->name ?? '—' }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $article->status }}">
                            {{ $article->status }}
                        </span>
                    </td>
                    <td style="color:#aaa;font-size:.8rem;">
                        {{ $article->updated_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;
                                    flex-wrap:wrap;align-items:center;">
                            {{-- View --}}
                            <a href="{{ route('articles.show', $article->slug) }}"
                               class="btn btn-gray"
                               style="font-size:.8rem;padding:.3rem .65rem;">
                               View
                            </a>

                            {{-- Edit — works on ALL statuses --}}
                            <a href="{{ route('my.article.edit', $article->id) }}"
                               class="btn btn-outline"
                               style="font-size:.8rem;padding:.3rem .65rem;">
                               Edit
                            </a>

                            @if($article->status === 'published')
                                <span style="font-size:.72rem;color:#888;
                                             background:#f0f0f0;
                                             padding:.2rem .5rem;
                                             border-radius:20px;">
                                    Edits go to review
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5"
                        style="text-align:center;color:#aaa;padding:2rem;">
                        No contributions yet. Start writing!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:1rem;">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline">Logout</button>
    </form>
</div>
@endsection