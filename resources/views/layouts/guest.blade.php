<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orbit CMS')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f0f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Orbit logo matching navbar */
        .auth-logo-link {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
            color: #111;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .orbit-icon {
            width: 32px;
            height: 32px;
            background: #111;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .orbit-ring {
            width: 16px;
            height: 16px;
            border: 2.5px solid #ffffff;
            border-radius: 50%;
            position: relative;
        }

        .orbit-ring::after {
            content: '';
            width: 5px;
            height: 5px;
            background: #ffffff;
            border-radius: 50%;
            position: absolute;
            top: -4px;
            left: 50%;
            animation: orbit-spin 2s linear infinite;
            transform-origin: 8px 8px;
        }

        @@keyframes orbit-spin {
            from { transform: rotate(0deg) translateX(8px) rotate(0deg); }
            to   { transform: rotate(360deg) translateX(8px) rotate(-360deg); }
        }

        .auth-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group { margin-bottom: 1.1rem; }

        .form-group label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: #444;
            margin-bottom: .35rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .form-control {
            width: 100%;
            padding: .65rem .9rem;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: .9rem;
            background: #fafafa;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #111;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.07);
        }

        select.form-control { cursor: pointer; }

        .btn-submit {
            width: 100%;
            padding: .75rem;
            background: #111111;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: .5rem;
            transition: background .2s, transform .15s;
        }

        .btn-submit:hover {
            background: #333;
            transform: translateY(-1px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: .83rem;
            color: #888;
        }

        .auth-footer a {
            color: #111;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        .error-msg {
            color: #c0392b;
            font-size: .8rem;
            margin-top: .3rem;
        }

        .flash-error {
            background: #f8d7da;
            color: #721c24;
            padding: .7rem 1rem;
            border-radius: 8px;
            font-size: .85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .85rem;
            color: #555;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        {{-- Logo matching navbar --}}
        <div class="auth-logo">
            <a href="{{ route('home') }}" class="auth-logo-link">
                <div class="orbit-icon">
                    <div class="orbit-ring"></div>
                </div>
                Orbit CMS
            </a>
        </div>

        {{ $slot }}
    </div>
</body>
</html>