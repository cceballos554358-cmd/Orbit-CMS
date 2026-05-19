@extends('layouts.app')
@section('title', 'Edit Category')

@section('content')
<div style="max-width:560px;margin:0 auto;">
    <div class="page-header">
        <h1>Edit Category</h1>
<button onclick="history.back()" class="btn btn-gray"
        style="cursor:pointer;border:none;">
    &larr; Back
</button>    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name"
                       class="form-control"
                       value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug"
                       class="form-control"
                       value="{{ old('slug', $category->slug) }}">
                @error('slug')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description"
                          class="form-control"
                          rows="4">{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-dark">Save Changes</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-gray">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('name').addEventListener('input', function () {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection