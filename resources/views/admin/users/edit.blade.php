@extends('layouts.app')
@section('title', 'Edit User')

@section('content')

<button onclick="history.back()" class="btn btn-gray"
        style="cursor:pointer;border:none;">
    &larr; Back
</button>

<div style="max-width:560px;margin:0 auto;">
    <div class="page-header">
        <h1>Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-gray">&larr; Back</a>
    </div>
    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    @foreach(['admin','editor','author','contributor','subscriber'] as $role)
                        <option value="{{ $role }}"
                            {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Optional password change --}}
            <div style="border-top:1px solid #eee;margin:1.25rem 0;padding-top:1.25rem;">
                <p style="font-size:.8rem;color:#888;margin-bottom:1rem;">
                    Leave password fields blank to keep the current password.
                </p>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Min. 8 characters">
                    @error('password')<p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Repeat new password">
                </div>
            </div>

            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn btn-dark">Save Changes</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-gray">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection