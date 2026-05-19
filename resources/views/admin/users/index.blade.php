@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="page-header">
    <h1>User Management</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-dark">+ New User</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Requested Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:#aaa;">{{ $user->id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td style="color:#666;">{{ $user->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
                    </td>
                    <td>
                        @if($user->desired_role && $user->desired_role !== $user->role)
                            <div style="display:flex;align-items:center;gap:.4rem;">
                                <span class="role-badge role-{{ $user->desired_role }}"
                                      style="opacity:.8;">
                                    {{ $user->desired_role }}
                                </span>
                                
                                <form method="POST"
                                      action="{{ route('admin.users.update', $user) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="name" value="{{ $user->name }}">
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <input type="hidden" name="role" value="{{ $user->desired_role }}">
                                    <button class="btn btn-dark"
                                            style="font-size:.72rem;padding:.2rem .5rem;">
                                        Approve
                                    </button>
                                </form>
                            </div>
                        @else
                            <span style="color:#aaa;font-size:.8rem;">— same</span>
                        @endif
                    </td>
                    <td style="color:#aaa;font-size:.8rem;">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="btn btn-gray"
                           style="font-size:.8rem;padding:.3rem .65rem;">View</a>
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="btn btn-outline"
                           style="font-size:.8rem;padding:.3rem .65rem;">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              style="display:inline"
                              onsubmit="return confirm('Delete {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger"
                                    style="font-size:.8rem;padding:.3rem .65rem;">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#aaa;padding:2rem;">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $users->links() }}</div>
</div>
@endsection