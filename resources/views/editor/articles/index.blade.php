@extends('layouts.app')
@section('title', 'Review Articles')

@section('content')
<div class="page-header">
    <h1>Review Articles</h1>
    <span style="font-size:.85rem;color:#888;">
        All articles across all statuses
    </span>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr id="article-row-{{ $article->id }}">
                    <td><strong>{{ Str::limit($article->title, 35) }}</strong></td>
                    <td style="font-size:.85rem;color:#666;">
                        {{ $article->author->name }}
                    </td>

                    <td style="font-size:.8rem;color:#888;">
                        {{ $article->categories->count() ? $article->categories->pluck('name')->join(', ') : '—' }}
                    </td>

                    <td>
                        <span class="badge badge-{{ $article->status }}"
                              id="status-badge-{{ $article->id }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td style="color:#aaa;font-size:.8rem;">
                        {{ $article->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;flex-wrap:wrap;">
                            <a href="{{ route('articles.show', $article->slug) }}"
                               class="btn btn-gray"
                               style="font-size:.78rem;padding:.25rem .55rem;">
                               Read
                            </a>

                            @if($article->status !== 'published')
                            <form method="POST"
                                  action="{{ route('editor.articles.status', $article) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="published">
                                <button class="btn btn-dark"
                                        style="font-size:.78rem;padding:.25rem .55rem;">
                                    Publish
                                </button>
                            </form>
                            @endif

                            @if($article->status !== 'draft')
                            <form method="POST"
                                  action="{{ route('editor.articles.status', $article) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="draft">
                                <button class="btn btn-danger"
                                        style="font-size:.78rem;padding:.25rem .55rem;">
                                    Reject
                                </button>
                            </form>
                            @endif

                            @if($article->status !== 'pending')
                            <form method="POST"
                                  action="{{ route('editor.articles.status', $article) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="pending">
                                <button class="btn btn-gray"
                                        style="font-size:.78rem;padding:.25rem .55rem;">
                                    Pending
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        style="text-align:center;color:#aaa;padding:2rem;">
                        No articles found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $articles->links() }}</div>
</div>
@endsection