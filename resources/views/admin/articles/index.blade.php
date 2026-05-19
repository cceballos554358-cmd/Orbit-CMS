@extends('layouts.app')
@section('title', 'All Articles')

@section('content')
<div class="page-header">
    <h1>All Articles</h1>
    <button onclick="history.back()" class="btn btn-gray" style="cursor:pointer;border:none;">
        &larr; Back
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td><strong>{{ Str::limit($article->title, 40) }}</strong></td>
                    <td style="color:#666;">{{ $article->author->name }}</td>
                    
                    {{-- FIXED: Supports multiple categories safely without crashing --}}
                    <td style="color:#888;font-size:.8rem;">
                        {{ $article->categories->count() ? $article->categories->pluck('name')->join(', ') : '—' }}
                    </td>

                    <td>
                        <span class="badge badge-{{ $article->status }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td style="color:#aaa;font-size:.8rem;">
                        {{ $article->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;">
                            <a href="{{ route('articles.show', $article->slug) }}"
                               class="btn btn-gray" style="font-size:.8rem;padding:.3rem .65rem;">View</a>
                            
                            <a href="{{ route('author.articles.edit', $article) }}"
                               class="btn btn-outline" style="font-size:.8rem;padding:.3rem .65rem;">Edit</a>
                            
                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                                  style="display:inline" onsubmit="return confirm('Delete this article permanently?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" style="font-size:.8rem;padding:.3rem .65rem;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#aaa;padding:2rem;">No articles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $articles->links() }}</div>
</div>
@endsection