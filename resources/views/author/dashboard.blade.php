@extends('layouts.app')
@section('title', 'Author Dashboard')

@section('content')
<div class="page-header">
    <h1>My Articles</h1>
    <a href="{{ route('author.articles.create') }}" class="btn btn-dark">
        + New Article
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $counts['draft'] }}</div>
        <div class="stat-label">Drafts</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $counts['pending'] }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $counts['published'] }}</div>
        <div class="stat-label">Published</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Categories</th>
                    <th>Tags</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myArticles as $article)
                <tr>
                    <td><strong>{{ Str::limit($article->title, 35) }}</strong></td>
                    <td>
                        <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
                            @foreach($article->categories as $cat)
                                <span style="font-size:.72rem;background:#f0f0f0;
                                             color:#555;padding:.15rem .5rem;
                                             border-radius:20px;">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                            @if($article->categories->isEmpty())
                                <span style="color:#aaa;font-size:.8rem;">—</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
                            @foreach($article->tags as $tag)
                                <span style="font-size:.72rem;background:#111;
                                             color:#fff;padding:.15rem .5rem;
                                             border-radius:20px;">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                            @if($article->tags->isEmpty())
                                <span style="color:#aaa;font-size:.8rem;">—</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $article->status }}">
                            {{ $article->status }}
                        </span>
                    </td>
                    <td style="color:#aaa;font-size:.8rem;">
                        {{ $article->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <a href="{{ route('articles.show', $article->slug) }}"
                               class="btn btn-gray"
                               style="font-size:.8rem;padding:.3rem .65rem;">
                               View
                            </a>
                            <a href="{{ route('my.article.edit', $article->id) }}"
                               class="btn btn-outline"
                               style="font-size:.8rem;padding:.3rem .65rem;">
                               Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        style="text-align:center;color:#aaa;padding:2rem;">
                        No articles yet.
                        <a href="{{ route('author.articles.create') }}"
                           style="color:#111;font-weight:600;">
                           Write your first article!
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection