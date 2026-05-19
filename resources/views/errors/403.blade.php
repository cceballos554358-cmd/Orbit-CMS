@extends('layouts.app')
@section('title', '403 — Forbidden')

@section('content')
<div style="text-align:center;padding:5rem 2rem;">
    <div style="font-size:5rem;font-weight:900;color:#e8e8e8;line-height:1;">403</div>
    <h1 style="font-size:1.5rem;font-weight:700;color:#111;margin:.75rem 0 .5rem;">
        Access Denied
    </h1>
    <p style="color:#888;margin-bottom:1.5rem;">
        You don't have permission to access this page.
    </p>
    <a href="{{ route('dashboard') }}" class="btn btn-dark">Go to Dashboard</a>
    <a href="{{ route('home') }}" class="btn btn-gray" style="margin-left:.5rem;">Go Home</a>
</div>
@endsection