<x-guest-layout>
@section('title', 'Register')

<p class="auth-title">Create an account</p>
<p style="text-align:center;font-size:.82rem;color:#888;margin-top:-.75rem;margin-bottom:1.25rem;">
    All new accounts start as <strong>Subscriber</strong> and can be upgraded by an Admin.
</p>

@if($errors->any())
    <div class="flash-error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name"
               class="form-control" value="{{ old('name') }}"
               placeholder="John Doe" required autofocus>
        @error('name')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email"
               class="form-control" value="{{ old('email') }}"
               placeholder="you@example.com" required>
        @error('email')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="desired_role">I want to join as</label>
        <select name="desired_role" id="desired_role" class="form-control">
            <option value="subscriber" {{ old('desired_role') == 'subscriber' ? 'selected' : '' }}>
                Subscriber — Read articles and comment
            </option>
            <option value="contributor" {{ old('desired_role') == 'contributor' ? 'selected' : '' }}>
                Contributor — Write draft articles
            </option>
            <option value="author" {{ old('desired_role') == 'author' ? 'selected' : '' }}>
                Author — Write and submit articles for review
            </option>
            <option value="editor" {{ old('desired_role') == 'editor' ? 'selected' : '' }}>
                Editor — Review and publish articles
            </option>
        </select>
        <p style="font-size:.75rem;color:#aaa;margin-top:.35rem;">
            Your account will start as <strong>Subscriber</strong> until an Admin approves your request.
        </p>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password"
               class="form-control" placeholder="Min. 8 characters" required>
        @error('password')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation"
               id="password_confirmation" class="form-control"
               placeholder="Repeat password" required>
    </div>

    <button type="submit" class="btn-submit">Create Account</button>
</form>

<div class="auth-footer">
    Already have an account?
    <a href="{{ route('login') }}">Login here</a>
</div>
</x-guest-layout>