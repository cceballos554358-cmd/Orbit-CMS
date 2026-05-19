@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<div class="page-header">
    <h1>Category Management</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">
        + New Category
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="color:#aaa;">{{ $category->id }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td style="color:#888;font-size:.8rem;">{{ $category->slug }}</td>
                    <td style="color:#666;font-size:.85rem;">
                        {{ Str::limit($category->description, 50) ?? '—' }}
                    </td>
                    <td>
                        <span style="font-weight:700;">{{ $category->articles_count }}</span>
                        <span style="color:#aaa;font-size:.8rem;"> articles</span>
                    </td>
                    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-outline"
                           style="font-size:.8rem;padding:.3rem .65rem;">Edit</a>
                        <form method="POST"
                              action="{{ route('admin.categories.destroy', $category) }}"
                              style="display:inline"
                              onsubmit="return confirm('Delete {{ $category->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger"
                                    style="font-size:.8rem;padding:.3rem .65rem;">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        style="text-align:center;color:#aaa;padding:2rem;">
                        No categories yet. Create your first one!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $categories->links() }}</div>
</div>
@endsection