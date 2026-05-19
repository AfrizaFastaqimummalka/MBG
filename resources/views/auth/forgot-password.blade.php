@extends('layouts.auth')

@section('content')

<div class="afu" style="text-align:center;margin-bottom:32px">
    <div style="width:72px;height:72px;background:linear-gradient(135deg,#2563EB 0%,#1D4ED8 100%);border-radius:22px;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;box-shadow:0 12px 36px rgba(37,99,235,0.30)">
        <i data-lucide="lock-keyhole" style="width:34px;height:34px;color:#fff;stroke-width:2"></i>
    </div>
    <h1 style="font-size:22px;font-weight:800;color:#0D1F3C;letter-spacing:-.5px">Reset Password</h1>
    <p style="font-size:14px;color:#6B8DB8;margin-top:5px;font-weight:500;max-width:280px;margin-inline:auto;line-height:1.6">
        Masukkan email kamu dan kami akan kirimkan link untuk reset password.
    </p>
</div>

<div class="afu d1" style="background:rgba(255,255,255,0.94);border:1px solid rgba(59,130,246,0.12);border-radius:20px;box-shadow:0 4px 24px rgba(30,64,175,0.07);padding:24px">

    @if(session('status'))
        <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:11px;padding:10px 14px;margin-bottom:14px;font-size:13px;color:#065F46;font-weight:600">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#FEF2F2;border:1px solid rgba(220,38,38,0.20);border-radius:11px;padding:10px 14px;margin-bottom:14px;font-size:13px;color:#991B1B;font-weight:600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" style="display:flex;flex-direction:column;gap:14px"
          onsubmit="setLoading('forgot-btn', true)">
        @csrf

        <div class="field">
            <label>Email</label>
            <div style="position:relative">
                <i data-lucide="mail" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#6B8DB8;pointer-events:none;width:15px;height:15px"></i>
                <input class="inp pl" type="email" name="email" placeholder="email@kamu.com"
                       value="{{ old('email') }}" autocomplete="email" required>
            </div>
        </div>

        <button type="submit" class="btn-primary tap" id="forgot-btn" style="margin-top:4px">
            <span>Kirim Link Reset</span>
            <i data-lucide="send" style="width:15px;height:15px"></i>
        </button>
    </form>
</div>

<div class="afu d2" style="margin-top:18px;text-align:center">
    <a href="{{ route('login') }}" style="font-size:13px;color:#6B8DB8;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:5px">
        <i data-lucide="arrow-left" style="width:13px;height:13px"></i>
        Kembali ke login
    </a>
</div>

@endsection
