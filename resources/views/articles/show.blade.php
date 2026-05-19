@extends('layouts.app')
@section('title', $article->title)

@section('content')
<div style="max-width:780px;margin:0 auto;">

    <button onclick="history.back()" class="btn btn-gray"
            style="margin-bottom:1.5rem;display:inline-block;cursor:pointer;border:none;">
        &larr; Back
    </button>

    <div class="card" style="padding:2.5rem;">
        {{-- Status + Categories + Tags --}}
        <div style="display:flex;flex-wrap:wrap;gap:.4rem;align-items:center;margin-bottom:1rem;">
            <span class="badge badge-{{ $article->status }}">
                {{ $article->status }}
            </span>
            
            @foreach($article->categories as $cat)
                <span style="font-size:.75rem;background:#f0f0f0;color:#555;padding:.2rem .6rem;border-radius:20px;">
                    {{ $cat->name }}
                </span>
            @endforeach
            
            @foreach($article->tags as $tag)
                <span style="font-size:.75rem;background:#111;color:#fff;padding:.2rem .6rem;border-radius:20px;">
                    #{{ $tag->name }}
                </span>
            @endforeach
        </div>

        {{-- Title --}}
        <h1 style="font-size:2rem;font-weight:800;color:#111;margin-bottom:.75rem;line-height:1.3;">
            {{ $article->title }}
        </h1>

        {{-- Meta --}}
        <p style="font-size:.85rem;color:#888;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid #f0f0f0;">
            By <strong>{{ $article->author->name }}</strong> &middot;
            {{ $article->published_at?->format('F d, Y') ?? $article->created_at->format('F d, Y') }}
        </p>

        {{-- Thumbnail --}}
        @if($article->thumbnail)
        <div style="text-align:center;margin-bottom:1.5rem;">
            <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}"
                 style="max-width:100%;height:auto;border-radius:10px;display:inline-block;box-shadow:0 4px 16px rgba(0,0,0,0.1);">
        </div>
        @endif

        {{-- Body --}}
        <div style="font-size:1rem;line-height:1.8;color:#333;">
            {!! nl2br(e($article->body)) !!}
        </div>

        {{-- Editor / Admin controls --}}
        @auth
        @if(auth()->user()->hasRole(['admin','editor']))
        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid #eee;">
            <p style="font-size:.8rem;font-weight:600;color:#444;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.75rem;">
                Update Status
            </p>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <form method="POST" action="{{ route('editor.articles.status', $article) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="published">
                    <button class="btn btn-dark">Publish</button>
                </form>
                <form method="POST" action="{{ route('editor.articles.status', $article) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="draft">
                    <button class="btn btn-gray">Reject to Draft</button>
                </form>
                @if(auth()->user()->hasRole('admin'))
                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                      onsubmit="return confirm('Delete this article permanently?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
                @endif
            </div>
        </div>
        @endif
        @endauth
    </div>

    {{-- Comments Section --}}
    <div class="card" style="margin-top:1.5rem;">
        <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;">
            Comments ({{ $comments->count() }})
        </h2>

        @forelse($comments as $comment)
        <div style="padding:1rem 0;border-bottom:1px solid #f0f0f0;">
            <div style="display:flex;gap:.75rem;">
                {{-- Avatar --}}
                <div style="width:36px;height:36px;background:#111;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($comment->author->name, 0, 1)) }}
                </div>

                <div style="flex:1;">
                    {{-- Header --}}
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.35rem;flex-wrap:wrap;">
                        <strong style="font-size:.875rem;">{{ $comment->author->name }}</strong>
                        <span style="font-size:.72rem;color:#aaa;">{{ $comment->created_at->diffForHumans() }}</span>
                        @if($comment->is_reported)
                            <span style="font-size:.68rem;background:#fef3cd;color:#856404;padding:.15rem .45rem;border-radius:20px;">Reported</span>
                        @endif
                    </div>

                    {{-- Comment body (Updated per instructions) --}}
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

                    {{-- Actions --}}
                    <div style="display:flex;gap:.5rem;margin-top:.6rem;flex-wrap:wrap;align-items:center;">
                        @auth
                        <button onclick="toggleReplyForm('reply-{{ $comment->id }}')" style="background:none;border:none;cursor:pointer;font-size:.78rem;color:#666;padding:0;display:flex;align-items:center;gap:.3rem;">
                            &#8617; Reply
                        </button>
                        
                        {{-- Report Button (Updated per instructions) --}}
                        @if($comment->user_id !== auth()->id())
                            @if($comment->isHiddenFromPublic())
                                <span style="font-size:.72rem;color:#aaa;background:#f0f0f0;
                                             padding:.2rem .5rem;border-radius:20px;">
                                    Hidden from public
                                </span>
                            @else
                                <form method="POST" action="{{ route('comments.report', $comment) }}">
                                    @csrf
                                    <button class="btn btn-gray"
                                            style="font-size:.72rem;padding:.2rem .55rem;"
                                            onclick="return confirm('Report this comment as inappropriate?')">
                                        &#9873; Report
                                        @if($comment->report_count > 0)
                                            ({{ $comment->report_count }}/3)
                                        @endif
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- ADMIN/EDITOR RESTORE & DELETE --}}
                        @if(auth()->user()->hasRole(['admin','editor']))
                            @if($comment->is_reported)
                                <form method="POST" action="{{ auth()->user()->hasRole('admin') ? route('admin.comments.approve', $comment) : route('editor.comments.approve', $comment) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-dark" style="font-size:.72rem;padding:.2rem .5rem;">Clear Report</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment and all its replies?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" style="font-size:.72rem;padding:.2rem .5rem;">Delete</button>
                            </form>
                        @endif
                        @endauth
                    </div>

                    {{-- Reply Form --}}
                    @auth
                    <div id="reply-{{ $comment->id }}" style="display:none;margin-top:.75rem;padding:.75rem;background:#f8f8f8;border-radius:10px;border-left:3px solid #111;">
                        <form method="POST" action="{{ route('comments.store', $article) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="body" class="form-control" rows="2" placeholder="Write your reply..." style="margin-bottom:.5rem;"></textarea>

                            <input type="file" name="media" id="replyMedia-{{ $comment->id }}" accept="image/*,.gif" style="display:none" onchange="previewReplyMedia(this, '{{ $comment->id }}')">
                            <div id="replyPreview-{{ $comment->id }}" style="display:none;margin-bottom:.5rem;">
                                <img id="replyImg-{{ $comment->id }}" src="" style="max-height:100px;border-radius:6px;border:1px solid #eee;">
                                <button type="button" onclick="clearReplyMedia('{{ $comment->id }}')" style="background:#c0392b;color:#fff;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:.7rem;margin-left:.4rem;">&times;</button>
                            </div>

                            <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
                                <button type="submit" class="btn btn-dark" style="font-size:.8rem;padding:.35rem .75rem;">Post Reply</button>
                                <button type="button" onclick="document.getElementById('replyMedia-{{ $comment->id }}').click()" class="btn btn-gray" style="font-size:.78rem;padding:.35rem .75rem;">&#128247; Image/GIF</button>
                                <button type="button" onclick="toggleReplyForm('reply-{{ $comment->id }}')" class="btn btn-gray" style="font-size:.78rem;padding:.35rem .75rem;">Cancel</button>
                            </div>
                        </form>
                    </div>
                    @endauth

                    {{-- REPLIES --}}
                    @if($comment->replies->count() > 0)
                        <div style="margin-top:.75rem;padding-left:.75rem;border-left:2px solid #e8e8e8;">
                            @foreach($comment->replies as $reply)
                                <div style="padding:.75rem 0;border-bottom:1px solid #f5f5f5;">
                                    <div style="display:flex;gap:.6rem;">
                                        <div style="width:28px;height:28px;background:#444;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($reply->author->name, 0, 1)) }}
                                        </div>
                                        <div style="flex:1;">
                                            <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.3rem;flex-wrap:wrap;">
                                                <strong style="font-size:.82rem;">{{ $reply->author->name }}</strong>
                                                <span style="font-size:.68rem;color:#aaa;">{{ $reply->created_at->diffForHumans() }}</span>
                                                @if($reply->is_reported)
                                                    <span style="font-size:.65rem;background:#fef3cd;color:#856404;padding:.1rem .4rem;border-radius:20px;">Reported</span>
                                                @endif
                                            </div>

                                            {{-- Reply body (Updated per instructions) --}}
                                            @if($reply->isHiddenFromPublic() && !auth()->user()?->hasRole(['admin','editor']))
                                                <div style="background:#f8f8f8;border-left:3px solid #ddd;
                                                            padding:.6rem .9rem;border-radius:0 8px 8px 0;">
                                                    <p style="font-size:.85rem;color:#bbb;font-style:italic;margin:0;">
                                                        This reply has been hidden after receiving multiple reports.
                                                    </p>
                                                </div>
                                            @elseif($reply->isFlagged() && auth()->user()?->hasRole(['admin','editor']))
                                                <div style="background:#fef9ec;border-left:3px solid #f0ad4e;
                                                            padding:.6rem .9rem;border-radius:0 8px 8px 0;margin-bottom:.4rem;">
                                                    <p style="font-size:.75rem;color:#856404;margin:0;">
                                                        &#9888; Flagged — {{ $reply->report_count }} report(s)
                                                    </p>
                                                </div>
                                                @if($reply->body)
                                                    <p style="font-size:.9rem;color:#333;line-height:1.6;margin-bottom:.4rem;">
                                                        {{ $reply->body }}
                                                    </p>
                                                @endif
                                                @if($reply->hasMedia())
                                                    <img src="{{ Storage::url($reply->media_path) }}"
                                                         style="max-width:100%;max-height:280px;border-radius:8px;
                                                                object-fit:contain;border:1px solid #eee;cursor:pointer;"
                                                         onclick="this.style.maxHeight=this.style.maxHeight==='none'?'280px':'none'">
                                                @endif
                                            @else
                                                @if($reply->body)
                                                    <p style="font-size:.9rem;color:#333;line-height:1.6;margin-bottom:.4rem;">
                                                        {{ $reply->body }}
                                                    </p>
                                                @endif
                                                @if($reply->hasMedia())
                                                    <img src="{{ Storage::url($reply->media_path) }}"
                                                         style="max-width:100%;max-height:280px;border-radius:8px;
                                                                object-fit:contain;border:1px solid #eee;cursor:pointer;"
                                                         onclick="this.style.maxHeight=this.style.maxHeight==='none'?'280px':'none'">
                                                @endif
                                            @endif

                                            <div style="display:flex;gap:.4rem;margin-top:.4rem;flex-wrap:wrap;">
                                                @auth
                                                
                                                {{-- Reply Report Button (Updated per instructions) --}}
                                                @if($reply->user_id !== auth()->id())
                                                    @if($reply->isHiddenFromPublic())
                                                        <span style="font-size:.72rem;color:#aaa;background:#f0f0f0;
                                                                     padding:.2rem .5rem;border-radius:20px;">
                                                            Hidden from public
                                                        </span>
                                                    @else
                                                        <form method="POST" action="{{ route('comments.report', $reply) }}">
                                                            @csrf
                                                            <button class="btn btn-gray"
                                                                    style="font-size:.72rem;padding:.2rem .55rem;"
                                                                    onclick="return confirm('Report this reply as inappropriate?')">
                                                                &#9873; Report
                                                                @if($reply->report_count > 0)
                                                                    ({{ $reply->report_count }}/3)
                                                                @endif
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                {{-- ADMIN/EDITOR RESTORE & DELETE FOR REPLIES --}}
                                                @if(auth()->user()->hasRole(['admin','editor']))
                                                    @if($reply->is_reported)
                                                        <form method="POST" action="{{ auth()->user()->hasRole('admin') ? route('admin.comments.approve', $reply) : route('editor.comments.approve', $reply) }}">
                                                            @csrf @method('PATCH')
                                                            <button class="btn btn-dark" style="font-size:.68rem;padding:.15rem .45rem;">Clear Report</button>
                                                        </form>
                                                    @endif

                                                    <form method="POST" action="{{ route('admin.comments.destroy', $reply) }}" onsubmit="return confirm('Delete this reply?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-danger" style="font-size:.68rem;padding:.15rem .45rem;">Delete</button>
                                                    </form>
                                                @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <p style="color:#aaa;font-size:.875rem;text-align:center;padding:1.5rem 0;">No comments yet. Be the first to comment!</p>
        @endforelse

        {{-- Main comment form --}}
        @auth
        <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #eee;">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;">Leave a Comment</h3>
            <form method="POST" action="{{ route('comments.store', $article) }}" enctype="multipart/form-data">
                @csrf
                <textarea name="body" class="form-control" rows="3" placeholder="Write your comment..."></textarea>

                <input type="file" name="media" id="mainMediaInput" accept="image/*,.gif" style="display:none" onchange="previewMainMedia(this)">
                <div id="mainMediaPreview" style="display:none;margin-bottom:.75rem;margin-top:.5rem;">
                    <img id="mainPreviewImg" src="" style="max-height:160px;max-width:100%;border-radius:8px;border:1px solid #eee;">
                    <button type="button" onclick="clearMainMedia()" style="background:#c0392b;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:.75rem;">&times;</button>
                </div>

                <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;margin-top:.5rem;">
                    <button type="submit" class="btn btn-dark">Post Comment</button>
                    <button type="button" onclick="document.getElementById('mainMediaInput').click()" class="btn btn-gray">&#128247; Image / GIF</button>
                </div>
            </form>
        </div>
        @else
        <div style="margin-top:1.25rem;padding:1rem;background:#f8f8f8;border-radius:8px;text-align:center;">
            <p style="font-size:.875rem;color:#888;">
                <a href="{{ route('login') }}" style="color:#111;font-weight:700;">Login</a>
                to leave a comment or reply.
            </p>
        </div>
        @endauth
    </div>
</div>

<script>
function toggleReplyForm(id) {
    const form = document.getElementById(id);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        if (form.style.display === 'block') form.querySelector('textarea').focus();
    }
}
function previewMainMedia(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('mainPreviewImg').src = e.target.result;
            document.getElementById('mainMediaPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function clearMainMedia() {
    document.getElementById('mainMediaInput').value = '';
    document.getElementById('mainPreviewImg').src = '';
    document.getElementById('mainMediaPreview').style.display = 'none';
}
function previewReplyMedia(input, id) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('replyImg-' + id).src = e.target.result;
            document.getElementById('replyPreview-' + id).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function clearReplyMedia(id) {
    document.getElementById('replyMedia-' + id).value = '';
    document.getElementById('replyImg-' + id).src = '';
    document.getElementById('replyPreview-' + id).style.display = 'none';
}
</script>
@endsection