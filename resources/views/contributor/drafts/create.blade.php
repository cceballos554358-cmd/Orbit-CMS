@extends('layouts.app')
@section('title', 'New Article')

@section('content')
<div style="max-width:720px;margin:0 auto;">
    <div class="page-header">
        <h1>Write New Article</h1>
        <a href="{{ route('author.dashboard') }}" class="btn btn-gray">&larr; Back</a>
    </div>

    <div class="card">
        @if(auth()->user()->hasRole('contributor') || auth()->user()->hasRole('subscriber'))
            <div style="background:#f8f8f8;border-left:3px solid #111;
                        padding:.75rem 1rem;border-radius:0 8px 8px 0;
                        margin-bottom:1.5rem;font-size:.85rem;color:#555;">
                Your draft will be saved and reviewed by an Editor before it can be published.
            </div>
        @endif

        <form method="POST" action="{{ route('author.articles.store') }}" enctype="multipart/form-data">
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
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="title" style="display:block; font-weight:600; margin-bottom:.5rem;">Draft Title</label>
                <input type="text" name="title" id="title"
                       class="form-control"
                       value="{{ old('title') }}"
                       placeholder="Enter your draft title..." required
                       style="width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px;">
                @error('title')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Thumbnail Image --}}
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="thumbnail" style="display:block; font-weight:600; margin-bottom:.5rem;">Thumbnail Image</label>
                <input type="file" name="thumbnail" id="thumbnail" 
                       class="form-control" accept="image/*"
                       style="width: 100%; padding: .4rem; border: 1px solid #ccc; border-radius: 4px; background: #fff;">
                <span style="font-size: .75rem; color: #888;">Max size: 2MB</span>
                @error('thumbnail')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categories --}}
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="categories" style="display:block; font-weight:600; margin-bottom:.5rem;">Categories</label>
                <select name="categories[]" id="categories" class="form-control" multiple
                        style="width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; min-height: 100px;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ (is_array(old('categories')) && in_array($cat->id, old('categories'))) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <span style="font-size: .75rem; color: #888;">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</span>
                @error('categories')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tags --}}
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="tags" style="display:block; font-weight:600; margin-bottom:.5rem;">Tags</label>
                <input type="text" name="tags" id="tags"
                       class="form-control"
                       value="{{ old('tags') }}"
                       placeholder="e.g. php, laravel, tutorial"
                       style="width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px;">
                <span style="font-size: .75rem; color: #888;">Separate multiple tags with a comma.</span>
                @error('tags')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Content Body --}}
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="body" style="display:block; font-weight:600; margin-bottom:.5rem;">Content</label>
                <textarea name="body" id="body"
                          class="form-control" rows="12"
                          placeholder="Write your draft content here..."
                          required
                          style="width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px;">{{ old('body') }}</textarea>
                @error('body')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Actions --}}
            <div style="padding-top:1rem;border-top:1px solid #eee;
                        display:flex;gap:.75rem;align-items:center;">
                <button type="submit" class="btn btn-dark" style="padding: .5rem 1rem; border-radius: 4px; border: none; background: #111; color: #fff; cursor: pointer;">
                    {{ auth()->user()->hasRole('subscriber') ? 'Save Draft' : 'Publish Article' }}
                </button>
                <a href="{{ route('author.dashboard') }}" class="btn btn-gray" style="padding: .5rem 1rem; text-decoration: none; color: #333; background: #eee; border-radius: 4px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection