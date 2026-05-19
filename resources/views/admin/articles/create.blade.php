@extends('layouts.app')
@section('title', 'New Article')

@section('content')
<div style="max-width:720px;margin:0 auto;">
    <div class="page-header">
        <h1>Write New Article</h1>
<button onclick="history.back()" class="btn btn-gray"
        style="cursor:pointer;border:none;">
    &larr; Back
</button>    </div>

    <div class="card">
        <form method="POST" action="{{ route('author.articles.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title') }}" placeholder="Enter article title..." required>
                @error('title')<p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">— No category —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="body">Content</label>
                <textarea name="body" id="body" class="form-control" rows="12"
                          placeholder="Write your article here..." required>{{ old('body') }}</textarea>
                @error('body')<p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="thumbnail">Thumbnail Image</label>
                <input type="file" name="thumbnail" id="thumbnail"
                       class="form-control" accept="image/*" style="padding:.4rem;">
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-dark">
                    {{ auth()->user()->isAuthor() ? 'Submit for Review' : 'Save Draft' }}
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-gray">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection