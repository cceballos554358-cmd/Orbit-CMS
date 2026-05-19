@extends('layouts.app')
@section('title', 'New Category')

@section('content')
<div style="max-width:560px;margin:0 auto;">
    <div class="page-header">
        <h1>New Category</h1>
        <button onclick="history.back()" class="btn btn-gray"
                style="cursor:pointer;border:none;">&larr; Back</button>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.categories.store') }}">
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

            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       placeholder="e.g. Technology" required>
                @error('name')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug">
                    Slug
                    <span style="font-weight:400;text-transform:none;
                                 letter-spacing:0;color:#aaa;">
                        (auto-filled from name)
                    </span>
                </label>
                <input type="text" name="slug" id="slug"
                       class="form-control"
                       value="{{ old('slug') }}"
                       placeholder="e.g. technology">
                @error('slug')
                    <p style="color:#c0392b;font-size:.8rem;margin-top:.3rem;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description"
                          class="form-control" rows="4"
                          placeholder="Brief description of this category...">{{ old('description') }}</textarea>
            </div>

            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn btn-dark">
                    Create Category
                </button>
                <button onclick="history.back()" type="button"
                        class="btn btn-gray"
                        style="cursor:pointer;border:none;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection