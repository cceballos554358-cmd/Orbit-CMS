@extends('layouts.app')
@section('title', 'Edit Article')

@section('content')
<div style="max-width:720px;margin:0 auto;">
    <div class="page-header">
        <h1>Edit Article</h1>
<button onclick="history.back()" class="btn btn-gray"
        style="cursor:pointer;border:none;">
    &larr; Back
</button>    </div>

    <div class="card">
        <form method="POST" action="{{ route('author.articles.update', $article) }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $article->title) }}" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">— No category —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="body">Content</label>
                <textarea name="body" id="body" class="form-control"
                          rows="12">{{ old('body', $article->body) }}</textarea>
            </div>

            <div class="form-group">
                <label for="thumbnail">Replace Thumbnail</label>
                @if($article->thumbnail)
                    <img src="{{ Storage::url($article->thumbnail) }}"
                         style="height:80px;border-radius:6px;margin-bottom:.5rem;display:block;">
                @endif
                <input type="file" name="thumbnail" id="thumbnail"
                       class="form-control" accept="image/*" style="padding:.4rem;">
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-dark">Save Changes</button>
                <a href="{{ route('dashboard') }}" class="btn btn-gray">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection