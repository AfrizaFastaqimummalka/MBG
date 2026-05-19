@extends('layouts.auth')

@section('content')

{{-- Logo --}}
<div class="afu" style="text-align:center;margin-bottom:32px">
    <div style="width:72px;height:72px;background:linear-gradient(135deg,#2563EB 0%,#1D4ED8 100%);border-radius:22px;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;box-shadow:0 12px 36px rgba(37,99,235,0.30)">
        <i data-lucide="bike" style="width:36px;height:36px;color:#fff;stroke-width:2"></i>
    </div>
    <h1 style="font-size:26px;font-weight:800;color:#0D1F3C;letter-spacing:-.6px">My Bike Garage</h1>
    <p style="font-size:14px;color:#6B8DB8;margin-top:5px;font-weight:500">Masuk ke garasi kamu</p>
</div>

{{-- Card --}}
<div class="afu d1" style="background:rgba(255,255,255,0.94);border:1px solid rgba(59,130,246,0.12);border-radius:20px;box-shadow:0 4px 24px rgba(30,64,175,0.07);padding:24px">

    {{-- Tab switcher --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px;background:rgba(59,130,246,0.07);border-radius:12px;padding:4px;margin-bottom:20px">
        <div style="padding:9px 0;border-radius:9px;background:#fff;color:#2563EB;font-size:14px;font-weight:700;text-align:center;box-shadow:0 1px 6px rgba(37,99,235,0.14)">Masuk</div>
        <a href="{{ route('register') }}" style="padding:9px 0;border-radius:9px;color:#6B8DB8;font-size:14px;font-weight:700;text-align:center;text-decoration:none">Daftar</a>
    </div>

    {{-- Error --}}
    @if($errors->any())
        <div style="background:#FEF2F2;border:1px solid rgba(220,38,38,0.20);border-radius:11px;padding:10px 14px;margin-bottom:14px;font-size:13px;color:#991B1B;font-weight:600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="login-form" style="display:flex;flex-direction:column;gap:14px"
          onsubmit="setLoading('login-btn', true)">
        @csrf

        <div class="field">
            <label>Email</label>
            <div style="position:relative">
                <i data-lucide="mail" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#6B8DB8;pointer-events:none;width:15px;height:15px"></i>
                <input class="inp pl" type="email" name="email" placeholder="email@kamu.com"
                       value="{{ old('email') }}" autocomplete="email" required>
            </div>
        </div>

        <div class="field">
            <label>Password</label>
            <div style="position:relative">
                <i data-lucide="lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#6B8DB8;pointer-events:none;width:15px;height:15px"></i>
                <input class="inp pl" type="password" name="password" placeholder="••••••"
                       autocomplete="current-password" required>
            </div>
        </div>

        <button type="submit" class="btn-primary tap" id="login-btn" style="margin-top:4px">
            <span>Masuk</span>
            <i data-lucide="arrow-right" style="width:16px;height:16px"></i>
        </button>
    </form>
</div>

<div class="afu d2" style="margin-top:18px;text-align:center">
    <a href="{{ route('password.request') }}" style="font-size:13px;color:#6B8DB8;font-weight:500;text-decoration:none">
        Lupa password?
    </a>
</div>

@endsection
