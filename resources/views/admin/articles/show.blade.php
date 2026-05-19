@extends('layouts.app')
@section('title', $article->title)

@section('content')
<div style="max-width:780px;margin:0 auto;">

<button onclick="history.back()" class="btn btn-gray"
        style="cursor:pointer;border:none;">
    &larr; Back
</button>

    <div class="card" style="padding:2.5rem;">
        <div style="display:flex;gap:.5rem;align-items:center;margin-bottom:1rem;">
            <span class="badge badge-{{ $article->status }}">{{ $article->status }}</span>
            @if($article->category)
                <span style="font-size:.8rem;color:#888;">{{ $article->category->name }}</span>
            @endif
        </div>

        <h1 style="font-size:2rem;font-weight:800;color:#111;margin-bottom:.75rem;line-height:1.3;">
            {{ $article->title }}
        </h1>

        <p style="font-size:.85rem;color:#888;margin-bottom:1.5rem;">
            By <strong>{{ $article->author->name }}</strong>
            &middot; {{ $article->published_at?->format('F d, Y') ?? $article->created_at->format('F d, Y') }}
        </p>

        @if($article->thumbnail)
            <img src="{{ Storage::url($article->thumbnail) }}"
                 style="width:100%;border-radius:10px;margin-bottom:1.5rem;max-height:400px;object-fit:cover;">
        @endif

        <div style="font-size:1rem;line-height:1.8;color:#333;">
            {!! nl2br(e($article->body)) !!}
        </div>

        @auth
        @if(auth()->user()->hasRole(['admin','editor']))
        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid #eee;display:flex;gap:.5rem;">
            <form method="POST" action="{{ route('editor.articles.status', $article) }}">
                @csrf @method('PATCH')
                <select name="status" class="form-control" style="width:auto;display:inline-block;">
                    <option value="draft"     {{ $article->status=='draft'     ? 'selected':'' }}>Draft</option>
                    <option value="pending"   {{ $article->status=='pending'   ? 'selected':'' }}>Pending</option>
                    <option value="published" {{ $article->status=='published' ? 'selected':'' }}>Published</option>
                </select>
                <button class="btn btn-dark" style="margin-left:.5rem;">Update Status</button>
            </form>
            @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                  onsubmit="return confirm('Delete this article?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
            @endif
        </div>
        @endif
        @endauth
    </div>

   {{-- Comments Section --}}
<div class="card" style="margin-top:1.5rem;">
    {{-- FIXED: Fallback to the article's direct relationships if the $comments variable wasn't passed --}}
    @php
        $viewComments = isset($comments) ? $comments : $article->comments()->whereNull('parent_id')->with(['author', 'replies.author'])->latest()->get();
    @endphp

    <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;">
        Comments ({{ $viewComments->count() }})
    </h2>

    @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:.6rem 1rem;
                    border-radius:8px;margin-bottom:1rem;font-size:.85rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#f8d7da;color:#721c24;padding:.6rem 1rem;
                    border-radius:8px;margin-bottom:1rem;font-size:.85rem;">
            {{ session('error') }}
        </div>
    @endif

    @forelse($viewComments as $comment)
    <div style="padding:1rem 0;border-bottom:1px solid #f0f0f0;">
        <div style="display:flex;gap:.75rem;">

            {{-- Avatar --}}
            <div style="width:36px;height:36px;background:#111;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;
                        color:#fff;font-size:.85rem;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($comment->author->name, 0, 1)) }}
            </div>

            <div style="flex:1;">
                {{-- Header --}}
                <div style="display:flex;align-items:center;gap:.5rem;
                            margin-bottom:.35rem;flex-wrap:wrap;">
                    <strong style="font-size:.875rem;">
                        {{ $comment->author->name }}
                    </strong>
                    <span style="font-size:.72rem;color:#aaa;">
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                    @if($comment->is_reported)
                        <span style="font-size:.68rem;background:#f8d7da;
                                     color:#721c24;padding:.15rem .45rem;
                                     border-radius:20px;font-weight:700;">
                            Under Review
                        </span>
                    @endif
                </div>

                {{-- Body --}}
                @if($comment->is_reported)
                    <div style="background:#fafafa;border-left:3px solid #e8e8e8;
                                padding:.6rem .9rem;border-radius:0 8px 8px 0;">
                        <p style="font-size:.85rem;color:#aaa;font-style:italic;margin:0;">
                            This comment has been reported and is under review by admins.
                        </p>
                    </div>
                @else
                    @if($comment->body)
                        <p style="font-size:.9rem;color:#333;line-height:1.6;
                                  margin-bottom:.4rem;">
                            {{ $comment->body }}
                        </p>
                    @endif
                    @if($comment->hasMedia())
                        <div style="margin-top:.5rem;">
                            <img src="{{ Storage::url($comment->media_path) }}"
                                 style="max-width:100%;max-height:280px;
                                        border-radius:8px;object-fit:contain;
                                        border:1px solid #eee;cursor:pointer;"
                                 onclick="this.style.maxHeight =
                                     this.style.maxHeight === 'none'
                                     ? '280px' : 'none'">
                            @if($comment->isGif())
                                <span style="font-size:.7rem;color:#888;
                                             display:block;margin-top:.25rem;">
                                    GIF
                                </span>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- Actions --}}
                <div style="display:flex;gap:.5rem;margin-top:.6rem;
                            flex-wrap:wrap;align-items:center;">

                    {{-- Reply button --}}
                    @auth
                    <button onclick="toggleReplyForm('reply-{{ $comment->id }}')"
                            style="background:none;border:none;cursor:pointer;
                                   font-size:.78rem;color:#666;padding:0;
                                   display:flex;align-items:center;gap:.3rem;">
                        &#8617; Reply
                        @if($comment->replies->count() > 0)
                            <span style="color:#aaa;">
                                ({{ $comment->replies->count() }})
                            </span>
                        @endif
                    </button>
                    @endauth

                    {{-- Report button --}}
                    @auth
                    @if(!$comment->is_reported && $comment->user_id !== auth()->id())
                    <form method="POST"
                          action="{{ route('comments.report', $comment) }}">
                        @csrf
                        <button class="btn btn-gray"
                                style="font-size:.72rem;padding:.2rem .55rem;"
                                onclick="return confirm('Report this comment as inappropriate?')">
                            &#9873; Report
                        </button>
                    </form>
                    @elseif($comment->is_reported && $comment->user_id === auth()->id())
                        <span style="font-size:.72rem;color:#aaa;">
                            You reported this comment
                        </span>
                    @endif

                    {{-- Admin/Editor controls --}}
                    @if(auth()->user()->hasRole(['admin','editor']))
                        @if($comment->is_reported)
                            <form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.comments.approve', $comment) : route('editor.comments.approve', $comment) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-dark" style="font-size:.72rem;padding:.2rem .55rem;">
                                    Clear Report
                                </button>
                            </form>
                        @endif

                        <form method="POST"
                              action="{{ route('admin.comments.destroy', $comment) }}"
                              onsubmit="return confirm('Delete this comment and all replies?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger"
                                    style="font-size:.72rem;padding:.2rem .55rem;">
                                Delete
                            </button>
                        </form>
                    @endif
                    @endauth

                    @guest
                    <a href="{{ route('login') }}"
                       style="font-size:.75rem;color:#aaa;">
                        Login to reply or report
                    </a>
                    @endguest
                </div>

                {{-- Reply Form --}}
                @auth
                <div id="reply-{{ $comment->id }}"
                     style="display:none;margin-top:.75rem;padding:.75rem;
                            background:#f8f8f8;border-radius:10px;
                            border-left:3px solid #111;">
                    <p style="font-size:.78rem;color:#555;margin-bottom:.5rem;">
                        Replying to <strong>{{ $comment->author->name }}</strong>
                    </p>
                    <form method="POST"
                          action="{{ route('comments.store', $article) }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id"
                               value="{{ $comment->id }}">
                        <textarea name="body" class="form-control" rows="2"
                                  placeholder="Write your reply..."
                                  style="margin-bottom:.5rem;"></textarea>

                        <input type="file" name="media"
                               id="replyMedia-{{ $comment->id }}"
                               accept="image/*,.gif" style="display:none"
                               onchange="previewReplyMedia(this, '{{ $comment->id }}')">

                        <div id="replyPreview-{{ $comment->id }}"
                             style="display:none;margin-bottom:.5rem;">
                            <img id="replyImg-{{ $comment->id }}" src=""
                                 style="max-height:100px;border-radius:6px;
                                        border:1px solid #eee;">
                            <button type="button"
                                    onclick="clearReplyMedia('{{ $comment->id }}')"
                                    style="background:#c0392b;color:#fff;
                                           border:none;border-radius:50%;
                                           width:18px;height:18px;cursor:pointer;
                                           font-size:.7rem;margin-left:.4rem;">
                                &times;
                            </button>
                        </div>

                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                            <button type="submit" class="btn btn-dark"
                                    style="font-size:.8rem;padding:.35rem .75rem;">
                                Post Reply
                            </button>
                            <button type="button"
                                    onclick="document.getElementById(
                                        'replyMedia-{{ $comment->id }}').click()"
                                    class="btn btn-gray"
                                    style="font-size:.78rem;padding:.35rem .75rem;">
                                &#128247; Image/GIF
                            </button>
                            <button type="button"
                                    onclick="toggleReplyForm('reply-{{ $comment->id }}')"
                                    class="btn btn-gray"
                                    style="font-size:.78rem;padding:.35rem .75rem;">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
                @endauth

                {{-- Replies --}}
                @if($comment->replies->count() > 0)
                <div style="margin-top:.75rem;padding-left:.75rem;
                            border-left:2px solid #e8e8e8;">
                    @foreach($comment->replies as $reply)
                    <div style="padding:.75rem 0;
                                border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:.6rem;">

                            <div style="width:28px;height:28px;background:#444;
                                        border-radius:50%;display:flex;
                                        align-items:center;justify-content:center;
                                        color:#fff;font-size:.72rem;font-weight:700;
                                        flex-shrink:0;">
                                {{ strtoupper(substr($reply->author->name, 0, 1)) }}
                            </div>

                            <div style="flex:1;">
                                <div style="display:flex;align-items:center;
                                            gap:.4rem;margin-bottom:.3rem;
                                            flex-wrap:wrap;">
                                    <strong style="font-size:.82rem;">
                                        {{ $reply->author->name }}
                                    </strong>
                                    <span style="font-size:.68rem;color:#aaa;">
                                        {{ $reply->created_at->diffForHumans() }}
                                    </span>
                                    @if($reply->is_reported)
                                        <span style="font-size:.65rem;
                                                     background:#f8d7da;
                                                     color:#721c24;
                                                     padding:.1rem .4rem;
                                                     border-radius:20px;
                                                     font-weight:700;">
                                            Under Review
                                        </span>
                                    @endif
                                </div>

                                @if($reply->is_reported)
                                    <p style="font-size:.82rem;color:#aaa;
                                              font-style:italic;">
                                        This reply is under review.
                                    </p>
                                @else
                                    @if($reply->body)
                                        <p style="font-size:.85rem;color:#333;
                                                  line-height:1.55;">
                                            {{ $reply->body }}
                                        </p>
                                    @endif
                                    @if($reply->hasMedia())
                                        <div style="margin-top:.4rem;">
                                            <img src="{{ Storage::url($reply->media_path) }}"
                                                 style="max-width:100%;max-height:200px;
                                                        border-radius:6px;
                                                        object-fit:contain;
                                                        border:1px solid #eee;
                                                        cursor:pointer;"
                                                 onclick="this.style.maxHeight =
                                                     this.style.maxHeight === 'none'
                                                     ? '200px' : 'none'">
                                        </div>
                                    @endif
                                @endif

                                {{-- Reply actions --}}
                                <div style="display:flex;gap:.4rem;
                                            margin-top:.4rem;flex-wrap:wrap;">
                                    @auth
                                    @if(!$reply->is_reported &&
                                        $reply->user_id !== auth()->id())
                                    <form method="POST"
                                          action="{{ route('comments.report', $reply) }}">
                                        @csrf
                                        <button class="btn btn-gray"
                                                style="font-size:.68rem;
                                                       padding:.15rem .45rem;"
                                                onclick="return confirm(
                                                    'Report this reply?')">
                                            &#9873; Report
                                        </button>
                                    </form>
                                    @endif
                                    
                                    {{-- Admin/Editor controls for Replies --}}
                                    @if(auth()->user()->hasRole(['admin','editor']))
                                        @if($reply->is_reported)
                                            <form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.comments.approve', $reply) : route('editor.comments.approve', $reply) }}">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-dark" style="font-size:.68rem;padding:.15rem .45rem;">
                                                    Clear Report
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST"
                                              action="{{ route('admin.comments.destroy', $reply) }}"
                                              onsubmit="return confirm('Delete this reply?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger"
                                                    style="font-size:.68rem;
                                                           padding:.15rem .45rem;">
                                                Delete
                                            </button>
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
    <div style="text-align:center;padding:2rem;color:#aaa;">
        <p style="font-size:.95rem;">No comments yet. Be the first to comment!</p>
    </div>
    @endforelse

    {{-- Main comment form --}}
    @auth
    <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #eee;">
        <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;">
            Leave a Comment
        </h3>
        <form method="POST" action="{{ route('comments.store', $article) }}"
              enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div style="background:#f8d7da;color:#721c24;padding:.6rem 1rem;
                            border-radius:8px;margin-bottom:.75rem;font-size:.82rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <textarea name="body" class="form-control" rows="3"
                          placeholder="Write your comment...">{{ old('body') }}</textarea>
            </div>

            <div id="mainMediaPreview"
                 style="display:none;margin-bottom:.75rem;">
                <div style="position:relative;display:inline-block;">
                    <img id="mainPreviewImg" src=""
                         style="max-height:160px;max-width:100%;
                                border-radius:8px;border:1px solid #eee;">
                    <button type="button" onclick="clearMainMedia()"
                            style="position:absolute;top:-6px;right:-6px;
                                   width:20px;height:20px;border-radius:50%;
                                   background:#c0392b;color:#fff;border:none;
                                   cursor:pointer;font-size:.75rem;">
                        &times;
                    </button>
                </div>
                <p id="mainMediaLabel"
                   style="font-size:.75rem;color:#888;margin-top:.25rem;"></p>
            </div>

            <input type="file" name="media" id="mainMediaInput"
                   accept="image/*,.gif" style="display:none"
                   onchange="previewMainMedia(this)">

            <div style="display:flex;align-items:center;
                        gap:.6rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-dark">
                    Post Comment
                </button>
                <button type="button"
                        onclick="document.getElementById(
                            'mainMediaInput').click()"
                        class="btn btn-gray"
                        style="display:flex;align-items:center;gap:.4rem;">
                    &#128247; Image / GIF
                </button>
                <span style="font-size:.75rem;color:#aaa;margin-left:auto;">
                    Max 5MB &middot; JPG, PNG, GIF, WEBP
                </span>
            </div>
        </form>
    </div>
    @else
    <div style="margin-top:1.25rem;padding:1rem;background:#f8f8f8;
                border-radius:8px;text-align:center;">
        <p style="font-size:.875rem;color:#555;">
            <a href="{{ route('login') }}"
               style="color:#111;font-weight:700;">Login</a>
            to leave a comment, reply, or report inappropriate content.
        </p>
    </div>
    @endauth
</div>

<script>
function toggleReplyForm(id) {
    const form = document.getElementById(id);
    if (form) {
        const isHidden = form.style.display === 'none' || form.style.display === '';
        form.style.display = isHidden ? 'block' : 'none';
        if (isHidden) {
            const ta = form.querySelector('textarea');
            if (ta) ta.focus();
        }
    }
}

function previewMainMedia(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('mainPreviewImg').src = e.target.result;
            document.getElementById('mainMediaPreview').style.display = 'block';
            const isGif = file.name.toLowerCase().endsWith('.gif');
            document.getElementById('mainMediaLabel').textContent =
                isGif ? 'GIF selected' : 'Image: ' + file.name;
        };
        reader.readAsDataURL(file);
    }
}

function clearMainMedia() {
    document.getElementById('mainMediaInput').value = '';
    document.getElementById('mainPreviewImg').src = '';
    document.getElementById('mainMediaPreview').style.display = 'none';
    document.getElementById('mainMediaLabel').textContent = '';
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