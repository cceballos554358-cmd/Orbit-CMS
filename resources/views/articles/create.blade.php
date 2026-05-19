@extends('layouts.app')
@section('title', 'New Article')

@section('content')
<div style="max-width:720px;margin:0 auto;">
    <div class="page-header">
        <h1>Write New Article</h1>
        <button onclick="history.back()" class="btn btn-gray"
                style="cursor:pointer;border:none;">
            &larr; Back
        </button>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('author.articles.store') }}"
              enctype="multipart/form-data">
            @csrf

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
                       value="{{ old('title') }}"
                       placeholder="Enter your article title..." required>
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
                    <label id="label-{{ $cat->id }}"
                           style="display:flex;align-items:center;gap:.4rem;
                                  padding:.35rem .75rem;border-radius:20px;
                                  background:#f0f0f0;cursor:pointer;
                                  font-size:.85rem;font-weight:400;
                                  text-transform:none;letter-spacing:0;
                                  color:#333;transition:background .2s,color .2s;
                                  {{ in_array($cat->id, old('categories', []))
                                     ? 'background:#111;color:#fff;' : '' }}">
                        <input type="checkbox" name="categories[]"
                               value="{{ $cat->id }}"
                               {{ in_array($cat->id, old('categories', []))
                                  ? 'checked' : '' }}
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
                          placeholder="Write your article here..."
                          required>{{ old('body') }}</textarea>
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
                        (comma separated e.g. health, tech, news)
                    </span>
                </label>
                <input type="text" name="tags" id="tags"
                       class="form-control"
                       value="{{ old('tags') }}"
                       placeholder="health, technology, lifestyle...">
                <div id="tagPreview"
                     style="display:flex;flex-wrap:wrap;gap:.4rem;
                            margin-top:.5rem;min-height:24px;">
                </div>
                <p style="font-size:.75rem;color:#aaa;margin-top:.3rem;">
                    Separate tags with commas. New tags are created automatically.
                </p>
            </div>

            {{-- Thumbnail --}}
            <div class="form-group">
                <label for="thumbnail">Thumbnail Image
                    <span style="font-weight:400;text-transform:none;
                                 letter-spacing:0;color:#aaa;">(optional)</span>
                </label>
                <input type="file" name="thumbnail" id="thumbnail"
                       class="form-control" accept="image/*"
                       style="padding:.4rem;"
                       onchange="previewThumbnail(this)">
                <div id="thumbPreview" style="display:none;margin-top:.75rem;">
                    <img id="thumbImg" src=""
                         style="height:120px;border-radius:8px;
                                object-fit:cover;border:1px solid #eee;">
                </div>
                <p style="font-size:.75rem;color:#aaa;margin-top:.3rem;">
                    Max 2MB. JPG, PNG, GIF, WEBP accepted.
                </p>
            </div>

            {{-- Submit --}}
            <div style="padding-top:1rem;border-top:1px solid #eee;
                        display:flex;gap:.75rem;align-items:center;">
                <button type="submit" class="btn btn-dark">
                    Publish Article
                </button>
                <button onclick="history.back()" type="button"
                        class="btn btn-gray"
                        style="cursor:pointer;border:none;">
                    Cancel
                </button>
                <span style="font-size:.78rem;color:#aaa;margin-left:auto;">
                    @if(auth()->user()->hasRole('subscriber'))
                        Saved as draft
                    @else
                        Will be published immediately
                    @endif
                </span>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle category chip style
function toggleCatLabel(checkbox) {
    const label = checkbox.parentElement;
    if (checkbox.checked) {
        label.style.background = '#111';
        label.style.color = '#fff';
    } else {
        label.style.background = '#f0f0f0';
        label.style.color = '#333';
    }
}

// Live tag preview
document.getElementById('tags').addEventListener('input', function() {
    const preview = document.getElementById('tagPreview');
    const tags    = this.value.split(',')
                              .map(t => t.trim())
                              .filter(t => t.length > 0);
    preview.innerHTML = tags.map(t =>
        `<span style="background:#111;color:#fff;padding:.2rem .65rem;
                      border-radius:20px;font-size:.75rem;
                      display:inline-flex;align-items:center;gap:.3rem;">
            #${t}
        </span>`
    ).join('');
});

// Word counter
document.getElementById('body').addEventListener('input', function() {
    const words = this.value.trim().split(/\s+/).filter(w => w.length > 0);
    const count = this.value.trim() === '' ? 0 : words.length;
    const readTime = Math.max(1, Math.ceil(count / 200));
    document.getElementById('wordCount').textContent =
        `${count} words · ${readTime} min read`;
});

// Thumbnail preview
function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('thumbImg').src = e.target.result;
            document.getElementById('thumbPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection