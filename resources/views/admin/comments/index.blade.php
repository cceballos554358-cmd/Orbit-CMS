@extends('layouts.app')
@section('title', 'Comments Management')

@section('content')
<div class="page-header">
    <h1>Comments Management</h1>
    <span style="font-size:.85rem;color:#888;">
        {{ $comments->total() }} total comments
    </span>
</div>

{{-- Report notification banner --}}
@if($reported > 0)
<div style="background:#fef3cd;border-left:4px solid #f0ad4e;
            padding:1rem 1.25rem;border-radius:8px;
            margin-bottom:1.5rem;display:flex;
            align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
    <div style="display:flex;align-items:center;gap:.75rem;">
        <span style="font-size:1.5rem;">&#9888;</span>
        <div>
            <p style="font-weight:700;color:#856404;font-size:.95rem;">
                {{ $reported }} Reported Comment{{ $reported > 1 ? 's' : '' }}
            </p>
            <p style="font-size:.82rem;color:#856404;margin-top:.1rem;">
                Users have flagged these comments as inappropriate.
                Review and take action below.
            </p>
        </div>
    </div>
    <a href="{{ route('admin.comments.index', ['filter' => 'reported']) }}"
       class="btn btn-dark" style="font-size:.82rem;">
        View Reported
    </a>
</div>
@endif

{{-- Filter tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;">
    <a href="{{ route('admin.comments.index') }}"
       class="btn {{ !request('filter') ? 'btn-dark' : 'btn-gray' }}"
       style="font-size:.8rem;">
       All Comments
    </a>
    <a href="{{ route('admin.comments.index', ['filter' => 'reported']) }}"
       class="btn {{ request('filter') === 'reported' ? 'btn-dark' : 'btn-gray' }}"
       style="font-size:.8rem;display:flex;align-items:center;gap:.4rem;">
       Reported
       @if($reported > 0)
           <span style="background:#c0392b;color:#fff;border-radius:20px;
                        padding:.1rem .45rem;font-size:.7rem;font-weight:700;">
               {{ $reported }}
           </span>
       @endif
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Comment</th>
                    <th>Author</th>
                    <th>Article</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                {{-- FIXED: Check is_reported boolean --}}
                <tr style="{{ $comment->is_reported ? 'background:#fffbf0;' : '' }}">

                    {{-- Comment body --}}
                    <td style="max-width:240px;">
                    @if($comment->isHiddenFromPublic() && !auth()->user()?->hasRole(['admin','editor']))
                        {{-- Hidden after 3 reports — public cannot see it --}}
                        <div style="background:#f8f8f8;border-left:3px solid #ddd;
                                    padding:.6rem .9rem;border-radius:0 8px 8px 0;">
                            <p style="font-size:.85rem;color:#bbb;font-style:italic;margin:0;">
                                This comment has been hidden after receiving multiple reports.
                            </p>
                        </div>
                    @elseif($comment->isFlagged() && auth()->user()?->hasRole(['admin','editor']))
                        {{-- Flagged but visible to admin/editor with warning --}}
                        <div style="background:#fef9ec;border-left:3px solid #f0ad4e;
                                    padding:.6rem .9rem;border-radius:0 8px 8px 0;margin-bottom:.4rem;">
                            <p style="font-size:.75rem;color:#856404;margin:0;">
                                &#9888; Flagged — {{ $comment->report_count }} report(s)
                            </p>
                        </div>
                        @if($comment->body)
                            <p style="font-size:.9rem;color:#333;line-height:1.6;margin-bottom:.4rem;">
                                {{ $comment->body }}
                            </p>
                        @endif
                        @if($comment->hasMedia())
                            <img src="{{ Storage::url($comment->media_path) }}"
                                style="max-width:100%;max-height:280px;border-radius:8px;
                                        object-fit:contain;border:1px solid #eee;cursor:pointer;"
                                onclick="this.style.maxHeight=this.style.maxHeight==='none'?'280px':'none'">
                        @endif
                    @else
                        {{-- Normal visible comment --}}
                        @if($comment->body)
                            <p style="font-size:.9rem;color:#333;line-height:1.6;margin-bottom:.4rem;">
                                {{ $comment->body }}
                            </p>
                        @endif
                        @if($comment->hasMedia())
                            <img src="{{ Storage::url($comment->media_path) }}"
                                style="max-width:100%;max-height:280px;border-radius:8px;
                                        object-fit:contain;border:1px solid #eee;cursor:pointer;"
                                onclick="this.style.maxHeight=this.style.maxHeight==='none'?'280px':'none'">
                        @endif
                    @endif
                    </td>

                    {{-- Author --}}
                    <td style="font-size:.85rem;">
                        <strong>{{ $comment->author->name }}</strong>
                    </td>

                    {{-- Article --}}
                    <td style="font-size:.8rem;color:#666;">
                        <a href="{{ route('articles.show', $comment->article->slug) }}"
                           style="color:#111;text-decoration:underline;">
                            {{ Str::limit($comment->article->title, 28) }}
                        </a>
                    </td>

                    {{-- Status --}}
                    <td>
                        @if($comment->is_reported)
                            <div style="display:flex;flex-direction:column;gap:.3rem;">
                                <span class="badge" style="background:#f8d7da;color:#721c24;">
                                    &#9888; Reported
                                </span>
                                <span style="font-size:.7rem;color:#aaa;">
                                    {{ $comment->report_count }}/3 reports
                                </span>
                                @if($comment->isHiddenFromPublic())
                                    <span style="font-size:.68rem;background:#f8d7da;
                                                 color:#721c24;padding:.15rem .4rem;
                                                 border-radius:20px;font-weight:700;">
                                        Hidden from public
                                    </span>
                                @else
                                    <span style="font-size:.68rem;color:#856404;">
                                        Still visible ({{ 3 - $comment->report_count }} left to hide)
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="badge badge-published">Active</span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td style="font-size:.8rem;color:#aaa;">
                        {{ $comment->created_at->format('M d, Y') }}<br>
                        <span style="font-size:.72rem;">{{ $comment->created_at->format('h:i A') }}</span>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div style="display:flex;flex-direction:column;gap:.4rem;">
                            {{-- FIXED: Check is_reported boolean --}}
                            @if($comment->is_reported)
                                {{-- Clear report --}}
                                <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-dark" style="font-size:.78rem;padding:.3rem .6rem;width:100%;">
                                        &#10003; Clear Report
                                    </button>
                                </form>
                            @endif

                            {{-- View in context --}}
                            <a href="{{ route('articles.show', $comment->article->slug) }}"
                               class="btn btn-gray" style="font-size:.78rem;padding:.3rem .6rem;text-align:center;">
                                View
                            </a>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                                  onsubmit="return confirm('Delete this comment permanently?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" style="font-size:.78rem;padding:.3rem .6rem;width:100%;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#aaa;padding:2rem;">
                        @if(request('filter') === 'reported')
                            No reported comments. All clear! &#10003;
                        @else
                            No comments yet.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $comments->links() }}</div>
</div>
@endsection