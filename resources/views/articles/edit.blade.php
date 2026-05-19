@extends('layouts.app')
@section('title', 'Edit Article')

@section('content')
<div style="max-width:720px;margin:0 auto;">
    <div class="page-header">
        <h1>Edit Article</h1>
        <button onclick="history.back()" class="btn btn-gray"
                style="cursor:pointer;border:none;">
            &larr; Back
        </button>
    </div>

    <div class="card">
        <form method="POST"
              action="/update-my-article/{{ $article->id }}"
              enctype="multipart/form-data">
            @csrf @method('PATCH')

            @if($errors->any())
                <div style="background:#f8d7da;color:#721c24;padding:.75rem 1rem;
                            border-radius:8px;margin-bottom:1rem;font-size:.875rem;">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin:.5rem 0 0 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Title --}}
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" name="title" id="title"
                       class="form-control"
                       value="{{ old('title', $article->title) }}" required>
                @error('title')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Multiple Categories --}}
            <div class="form-group">
                <label>Categories
                    <span style="font-weight:400;text-transform:none;
                                 letter-spacing:0;color:#aaa;">
                        (select one or more)
                    </span>
                </label>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;
                            padding:.75rem;border:1.5px solid #ddd;
                            border-radius:8px;background:#fafafa;">
                    @foreach($categories as $cat)
                    @php
                        $isSelected = old('categories')
                            ? in_array($cat->id, old('categories', []))
                            : $article->categories->contains($cat->id);
                    @endphp
                    <label style="display:flex;align-items:center;gap:.4rem;
                                  padding:.35rem .75rem;border-radius:20px;
                                  cursor:pointer;font-size:.85rem;font-weight:400;
                                  text-transform:none;letter-spacing:0;
                                  transition:background .2s,color .2s;
                                  background:{{ $isSelected ? '#111' : '#f0f0f0' }};
                                  color:{{ $isSelected ? '#fff' : '#333' }};">
                        <input type="checkbox" name="categories[]"
                               value="{{ $cat->id }}"
                               {{ $isSelected ? 'checked' : '' }}
                               onchange="toggleCatLabel(this)"
                               style="display:none;">
                        {{ $cat->name }}
                    </label>
                    @endforeach
                </div>
                <p style="font-size:.75rem;color:#aaa;margin-top:.3rem;">
                    Click to select. You can pick multiple categories.
                </p>
            </div>

            {{-- Body --}}
            <div class="form-group">
                <label for="body">Content</label>
                <textarea name="body" id="body"
                          class="form-control" rows="14"
                          required>{{ old('body', $article->body) }}</textarea>
                <div style="display:flex;justify-content:flex-end;
                            margin-top:.3rem;">
                    <span id="wordCount"
                          style="font-size:.75rem;color:#aaa;">
                        0 words
                    </span>
                </div>
                @error('body')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tags --}}
            <div class="form-group">
                <label for="tags">Tags
                    <span style="font-weight:400;text-transform:none;
                                 letter-spacing:0;color:#aaa;">
                        (comma separated)
                    </span>
                </label>
                <input type="text" name="tags" id="tags"
                       class="form-control"
                       value="{{ old('tags', $article->tags->pluck('name')->join(', ')) }}"
                       placeholder="health, technology, lifestyle...">
                <div id="tagPreview"
                     style="display:flex;flex-wrap:wrap;gap:.4rem;
                            margin-top:.5rem;min-height:24px;">
                    @foreach($article->tags as $tag)
                        <span style="background:#111;color:#fff;
                                     padding:.2rem .65rem;border-radius:20px;
                                     font-size:.75rem;">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                <p style="font-size:.75rem;color:#aaa;margin-top:.3rem;">
                    Separate tags with commas.
                </p>
            </div>

            {{-- Thumbnail --}}
            <div class="form-group">
                <label for="thumbnail">Replace Thumbnail
                    <span style="font-weight:400;text-transform:none;
                                 letter-spacing:0;color:#aaa;">(optional)</span>
                </label>
                @if($article->thumbnail)
                    <div style="margin-bottom:.75rem;">
                        <img src="{{ Storage::url($article->thumbnail) }}"
                             id="thumbImg"
                             style="height:120px;border-radius:8px;
                                    object-fit:cover;border:1px solid #eee;">
                        <p style="font-size:.75rem;color:#aaa;margin-top:.3rem;">
                            Current thumbnail. Upload new to replace.
                        </p>
                    </div>
                @else
                    <div id="thumbPreview" style="display:none;margin-bottom:.75rem;">
                        <img id="thumbImg" src=""
                             style="height:120px;border-radius:8px;
                                    object-fit:cover;border:1px solid #eee;">
                    </div>
                @endif
                <input type="file" name="thumbnail" id="thumbnail"
                       class="form-control" accept="image/*"
                       style="padding:.4rem;"
                       onchange="previewThumbnail(this)">
            </div>

            {{-- Status — admin/editor only --}}
            @if(auth()->user()->hasRole(['admin','editor']))
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="draft"
                        {{ old('status', $article->status) == 'draft'
                           ? 'selected' : '' }}>Draft</option>
                    <option value="pending"
                        {{ old('status', $article->status) == 'pending'
                           ? 'selected' : '' }}>Pending Review</option>
                    <option value="published"
                        {{ old('status', $article->status) == 'published'
                           ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            @endif

            {{-- Info banner for contributor/author editing published --}}
            @if(auth()->user()->hasRole(['contributor','author'])
                && $article->status === 'published')
            <div style="background:#fef3cd;color:#856404;padding:.75rem 1rem;
                        border-radius:8px;margin-bottom:1rem;font-size:.85rem;
                        border-left:3px solid #f0ad4e;">
                &#9888; You are editing a published article. Saving will send
                it back to <strong>pending review</strong> before it goes live again.
            </div>
            @endif

            {{-- Submit --}}
            <div style="padding-top:1rem;border-top:1px solid #eee;
                        display:flex;gap:.75rem;align-items:center;">
                <button type="submit" class="btn btn-dark">
                    Save Changes
                </button>
                <button onclick="history.back()" type="button"
                        class="btn btn-gray"
                        style="cursor:pointer;border:none;">
                    Cancel
                </button>
                <span style="font-size:.78rem;color:#aaa;margin-left:auto;">
                    Last updated:
                    {{ $article->updated_at->format('M d, Y h:i A') }}
                </span>
            </div>
        </form>
    </div>
</div>

<script>
function toggleCatLabel(checkbox) {
    const label = checkbox.parentElement;
    if (checkbox.checked) {
        label.style.background = '#111';
        label.style.color      = '#fff';
    } else {
        label.style.background = '#f0f0f0';
        label.style.color      = '#333';
    }
}

// Live tag preview
const tagsInput = document.getElementById('tags');
if (tagsInput) {
    tagsInput.addEventListener('input', function() {
        const preview = document.getElementById('tagPreview');
        const tags    = this.value.split(',')
                                  .map(t => t.trim())
                                  .filter(t => t.length > 0);
        preview.innerHTML = tags.map(t =>
            `<span style="background:#111;color:#fff;padding:.2rem .65rem;
                          border-radius:20px;font-size:.75rem;">
                #${t}
            </span>`
        ).join('');
    });
}

// Word counter
const bodyInput = document.getElementById('body');
if (bodyInput) {
    function updateWordCount() {
        const words = bodyInput.value.trim()
                               .split(/\s+/)
                               .filter(w => w.length > 0);
        const count    = bodyInput.value.trim() === '' ? 0 : words.length;
        const readTime = Math.max(1, Math.ceil(count / 200));
        document.getElementById('wordCount').textContent =
            `${count} words · ${readTime} min read`;
    }
    updateWordCount();
    bodyInput.addEventListener('input', updateWordCount);
}

// Thumbnail preview
function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img     = document.getElementById('thumbImg');
            const preview = document.getElementById('thumbPreview');
            img.src       = e.target.result;
            if (preview) preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection