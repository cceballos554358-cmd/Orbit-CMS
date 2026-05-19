@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div style="max-width:580px;margin:0 auto;">
    <div class="page-header">
        <h1>My Profile</h1>
<button onclick="history.back()" class="btn btn-gray"
        style="cursor:pointer;border:none;">
    &larr; Back
</button>    </div>

    {{-- Profile info --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;">
            Account Information
        </h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')<p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')<p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Role</label>
                <input type="text" class="form-control"
                       value="{{ ucfirst(auth()->user()->role) }}" disabled
                       style="background:#f0f0f0;color:#888;cursor:not-allowed;">
                <p style="font-size:.75rem;color:#aaa;margin-top:.3rem;">
                    Role can only be changed by an administrator.
                </p>
            </div>

            <button type="submit" class="btn btn-dark">Save Changes</button>
        </form>
    </div>

    {{-- Request role change --}}
    <div class="card" style="margin-top:1.5rem;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:.5rem;">
            Request Role Change
        </h2>
        <p style="font-size:.82rem;color:#888;margin-bottom:1.25rem;">
            Your current role is
            <span class="role-badge role-{{ auth()->user()->role }}">
                {{ auth()->user()->role }}
            </span>
            — request a different role and an Admin will review it.
        </p>
        <form method="POST" action="{{ route('profile.requestRole') }}">
            @csrf @method('PATCH')
            <div class="form-group">
                <label>Requested Role</label>
                <select name="desired_role" class="form-control">
                    @foreach(['subscriber','contributor','author','editor'] as $role)
                        <option value="{{ $role }}"
                            {{ auth()->user()->desired_role === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-dark">Submit Request</button>
            @if(auth()->user()->desired_role !== auth()->user()->role)
                <p style="font-size:.78rem;color:#856404;margin-top:.75rem;
                        background:#fef3cd;padding:.5rem .75rem;border-radius:6px;">
                    Pending request: <strong>{{ auth()->user()->desired_role }}</strong>
                    — waiting for Admin approval.
                </p>
            @endif
        </form>
    </div>


    {{-- Change password --}}
    <div class="card">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;">
            Change Password
        </h2>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf @method('PATCH')

            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control"
                       placeholder="Enter current password" required>
                @error('current_password')
                    <p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" required>
                @error('password')
                    <p style="color:#c0392b;font-size:.8rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat new password" required>
            </div>

            <button type="submit" class="btn btn-dark">Update Password</button>
        </form>
    </div>
</div>
@endsection