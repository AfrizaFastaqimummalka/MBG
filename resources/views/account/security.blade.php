@extends('layouts.app')

@section('content')

{{-- ─── HERO ─────────────────────────────────────────────────────────────── --}}
<div class="hero" style="padding-bottom:24px">
    <div class="hero-circle" style="top:-40px;right:-30px;width:160px;height:160px"></div>
    <div class="hero-circle" style="bottom:-50px;left:-20px;width:180px;height:180px;background:rgba(255,255,255,0.04)"></div>

    <div style="position:relative;z-index:1" class="afu">
        <a href="{{ route('account') }}" style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,0.70);font-size:12px;font-weight:600;text-decoration:none;margin-bottom:8px">
            <i data-lucide="arrow-left" style="width:14px;height:14px"></i> Kembali
        </a>
        <h1 style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-.4px">Keamanan</h1>
        <p style="font-size:12px;color:rgba(255,255,255,0.65);margin-top:2px">Password & sesi login</p>
    </div>
</div>

{{-- ─── CONTENT ──────────────────────────────────────────────────────────── --}}
<div style="padding:16px;display:flex;flex-direction:column;gap:12px">

    {{-- Flash --}}
    @if(session('success'))
    <div style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.30);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:8px">
        <i data-lucide="check-circle" style="width:16px;height:16px;color:#16A34A;flex-shrink:0"></i>
        <p style="font-size:13px;color:#15803D;font-weight:600">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Ganti Password --}}
    <div class="card afu" style="overflow:hidden">
        <div style="padding:10px 16px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border-bottom:1px solid rgba(59,130,246,0.10)">
            <p class="sec-label">Ganti Password</p>
        </div>
        <div style="padding:16px">
            <form method="POST" action="{{ route('security.password') }}">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div style="margin-bottom:12px">
                    <label style="font-size:12px;font-weight:600;color:#2D4B78;display:block;margin-bottom:6px">Password Lama</label>
                    <div style="display:flex;align-items:center;gap:8px;background:#F8FAFF;border:1.5px solid {{ $errors->has('current_password') ? '#DC2626' : 'rgba(59,130,246,0.20)' }};border-radius:10px;padding:10px 12px">
                        <i data-lucide="lock" style="width:15px;height:15px;color:#6B8DB8;flex-shrink:0"></i>
                        <input type="password" name="current_password" placeholder="Masukkan password lama"
                               style="flex:1;border:none;background:none;font-size:14px;color:#0D1F3C;font-weight:600;outline:none">
                        <i data-lucide="eye" onclick="togglePass(this)" style="width:15px;height:15px;color:#6B8DB8;cursor:pointer;flex-shrink:0"></i>
                    </div>
                    @error('current_password')
                    <p style="font-size:11px;color:#DC2626;margin-top:4px">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div style="margin-bottom:12px">
                    <label style="font-size:12px;font-weight:600;color:#2D4B78;display:block;margin-bottom:6px">Password Baru</label>
                    <div style="display:flex;align-items:center;gap:8px;background:#F8FAFF;border:1.5px solid {{ $errors->has('password') ? '#DC2626' : 'rgba(59,130,246,0.20)' }};border-radius:10px;padding:10px 12px">
                        <i data-lucide="lock" style="width:15px;height:15px;color:#6B8DB8;flex-shrink:0"></i>
                        <input type="password" name="password" id="new-password" placeholder="Minimal 8 karakter"
                               style="flex:1;border:none;background:none;font-size:14px;color:#0D1F3C;font-weight:600;outline:none">
                        <i data-lucide="eye" onclick="togglePass(this)" style="width:15px;height:15px;color:#6B8DB8;cursor:pointer;flex-shrink:0"></i>
                    </div>
                    @error('password')
                    <p style="font-size:11px;color:#DC2626;margin-top:4px">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div style="margin-bottom:16px">
                    <label style="font-size:12px;font-weight:600;color:#2D4B78;display:block;margin-bottom:6px">Konfirmasi Password Baru</label>
                    <div style="display:flex;align-items:center;gap:8px;background:#F8FAFF;border:1.5px solid rgba(59,130,246,0.20);border-radius:10px;padding:10px 12px">
                        <i data-lucide="lock" style="width:15px;height:15px;color:#6B8DB8;flex-shrink:0"></i>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                               style="flex:1;border:none;background:none;font-size:14px;color:#0D1F3C;font-weight:600;outline:none">
                        <i data-lucide="eye" onclick="togglePass(this)" style="width:15px;height:15px;color:#6B8DB8;cursor:pointer;flex-shrink:0"></i>
                    </div>
                </div>

                {{-- Strength indicator --}}
                <div style="margin-bottom:16px">
                    <div style="display:flex;gap:4px;margin-bottom:4px">
                        <div id="str-1" style="flex:1;height:3px;border-radius:99px;background:#E2E8F0;transition:.3s"></div>
                        <div id="str-2" style="flex:1;height:3px;border-radius:99px;background:#E2E8F0;transition:.3s"></div>
                        <div id="str-3" style="flex:1;height:3px;border-radius:99px;background:#E2E8F0;transition:.3s"></div>
                        <div id="str-4" style="flex:1;height:3px;border-radius:99px;background:#E2E8F0;transition:.3s"></div>
                    </div>
                    <p id="str-label" style="font-size:11px;color:#6B8DB8;font-weight:500"></p>
                </div>

                <button type="submit"
                        style="width:100%;padding:13px;background:linear-gradient(135deg,#2563EB,#1D4ED8);border:none;border-radius:12px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
                    <i data-lucide="shield-check" style="width:15px;height:15px"></i>
                    Perbarui Password
                </button>
            </form>
        </div>
    </div>

    {{-- Keluar Sesi Lain --}}
    <div class="card afu d1" style="overflow:hidden">
        <div style="padding:10px 16px;background:linear-gradient(135deg,#FFF7ED 0%,#FFEDD5 100%);border-bottom:1px solid rgba(234,88,12,0.10)">
            <p style="font-size:10px;font-weight:800;color:#9A3412;text-transform:uppercase;letter-spacing:.08em">Sesi Aktif</p>
        </div>
        <div style="padding:16px">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(234,88,12,0.10);border:1px solid rgba(234,88,12,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i data-lucide="monitor" style="width:16px;height:16px;color:#EA580C"></i>
                </div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:#0D1F3C">Keluar dari Semua Perangkat Lain</p>
                    <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Logout semua sesi kecuali yang sekarang</p>
                </div>
            </div>

            <form method="POST" action="{{ route('security.logout-others') }}">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:8px;align-items:center;background:#FFF7ED;border:1.5px solid {{ $errors->has('password') ? '#DC2626' : 'rgba(234,88,12,0.20)' }};border-radius:10px;padding:10px 12px;margin-bottom:10px">
                    <i data-lucide="key" style="width:15px;height:15px;color:#EA580C;flex-shrink:0"></i>
                    <input type="password" name="password" placeholder="Konfirmasi dengan password kamu"
                           style="flex:1;border:none;background:none;font-size:13px;color:#0D1F3C;font-weight:600;outline:none">
                </div>
                @error('password')
                <p style="font-size:11px;color:#DC2626;margin-bottom:8px">{{ $message }}</p>
                @enderror

                <button type="submit"
                        style="width:100%;padding:12px;background:rgba(234,88,12,0.10);border:1.5px solid rgba(234,88,12,0.25);border-radius:12px;color:#EA580C;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
                    <i data-lucide="log-out" style="width:14px;height:14px"></i>
                    Keluarkan Perangkat Lain
                </button>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
// Toggle show/hide password
function togglePass(icon) {
    const input = icon.closest('div').querySelector('input');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');
    lucide.createIcons();
}

// Password strength
document.getElementById('new-password')?.addEventListener('input', function () {
    const val = this.value;
    let score = 0;
    if (val.length >= 8)             score++;
    if (/[A-Z]/.test(val))           score++;
    if (/[0-9]/.test(val))           score++;
    if (/[^A-Za-z0-9]/.test(val))   score++;

    const colors = ['#EF4444','#F97316','#EAB308','#22C55E'];
    const labels = ['Lemah','Cukup','Kuat','Sangat Kuat'];

    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById('str-' + i);
        el.style.background = i <= score ? colors[score - 1] : '#E2E8F0';
    }
    document.getElementById('str-label').textContent = val.length ? labels[score - 1] ?? '' : '';
});
</script>
@endpush

@endsection
