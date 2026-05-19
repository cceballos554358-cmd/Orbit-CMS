<x-guest-layout>
    @section('title', 'Login')

    <p class="auth-title">Welcome back</p>

    {{-- Error messages --}}
    @if($errors->any())
        <div class="flash-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email"
                   class="form-control" value="{{ old('email') }}"
                   placeholder="you@example.com" required autofocus>
            @error('email')<p class="error-msg">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password"
                   class="form-control" placeholder="••••••••" required>
            @error('password')<p class="error-msg">{{ $message }}</p>@enderror
        </div>

        <div class="checkbox-group">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" style="text-transform:none;letter-spacing:0;font-weight:400;">
                Remember me
            </label>
        </div>

        <button type="submit" class="btn-submit">Login</button>
    </form>

    <div class="auth-footer">
        Don't have an account?
        <a href="{{ route('register') }}">Register here</a>
    </div>
</x-guest-layout>