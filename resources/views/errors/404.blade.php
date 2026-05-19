@extends('layouts.app')
@section('title', '404 — Not Found')

@section('content')
<div style="text-align:center;padding:5rem 2rem;">
    <div style="font-size:5rem;font-weight:900;color:#e8e8e8;line-height:1;">404</div>
    <h1 style="font-size:1.5rem;font-weight:700;color:#111;margin:.75rem 0 .5rem;">
        Page Not Found
    </h1>
    <p style="color:#888;margin-bottom:1.5rem;">
        The page you're looking for doesn't exist or has been moved.
    </p>
    <a href="{{ route('home') }}" class="btn btn-dark">Go Home</a>
</div>
@endsection