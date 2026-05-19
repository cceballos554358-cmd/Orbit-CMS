@extends('layouts.app')
@section('title', 'View User')

@section('content')
<div style="max-width:560px;margin:0 auto;">
    <div class="page-header">
        <h1>User Details</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-gray">&larr; Back</a>
    </div>
    <div class="card">
        <div style="margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #f0f0f0;">
            <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Name</p>
            <p style="font-size:1rem;font-weight:600;">{{ $user->name }}</p>
        </div>
        <div style="margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #f0f0f0;">
            <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Email</p>
            <p style="font-size:1rem;">{{ $user->email }}</p>
        </div>
        <div style="margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #f0f0f0;">
            <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Role</p>
            <span class="role-badge role-{{ $user->role }}">{{ $user->role }}</span>
        </div>
        <div>
            <p style="font-size:.75rem;color:#aaa;text-transform:uppercase;letter-spacing:.5px;">Joined</p>
            <p style="font-size:1rem;">{{ $user->created_at->format('F d, Y') }}</p>
        </div>
        <div style="margin-top:1.5rem;display:flex;gap:.75rem;">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-dark">Edit User</a>
        </div>
    </div>
</div>
@endsection