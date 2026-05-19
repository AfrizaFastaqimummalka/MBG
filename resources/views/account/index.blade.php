@extends('layouts.app')

@section('content')
@php use App\Helpers\FormatHelper as F; @endphp

{{-- ─── HERO ─────────────────────────────────────────────────────────────── --}}
<div class="hero" style="padding-bottom:24px">
    <div class="hero-circle" style="top:-40px;right:-30px;width:160px;height:160px"></div>
    <div class="hero-circle" style="bottom:-50px;left:-20px;width:180px;height:180px;background:rgba(255,255,255,0.04)"></div>

    <div style="position:relative;z-index:1" class="afu">
        <p style="font-size:11px;color:rgba(255,255,255,0.65);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Pengaturan</p>
        <h1 style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-.4px">Akun</h1>
    </div>

    {{-- Profile card --}}
    <div class="afu d1" style="display:flex;align-items:center;gap:14px;margin-top:16px;background:rgba(255,255,255,0.13);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.22);border-radius:16px;padding:14px 16px;position:relative;z-index:1">
        <div style="width:50px;height:50px;border-radius:16px;background:rgba(255,255,255,0.22);border:2px solid rgba(255,255,255,0.35);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <span style="font-size:18px;font-weight:800;color:#fff">{{ F::initials($user->name) }}</span>
        </div>
        <div style="flex:1;min-width:0">
            <p style="font-size:16px;font-weight:800;color:#fff;letter-spacing:-.3px">{{ $user->name }}</p>
            <p style="font-size:12px;color:rgba(255,255,255,0.65);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $user->email }}</p>
        </div>
        <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center">
            <i data-lucide="user" style="width:16px;height:16px;color:rgba(255,255,255,0.80)"></i>
        </div>
    </div>
</div>

{{-- ─── CONTENT ──────────────────────────────────────────────────────────── --}}
<div style="padding:16px 16px 0;display:flex;flex-direction:column;gap:12px">

    {{-- Ringkasan --}}
    <div class="card afu" style="overflow:hidden">
        <div style="padding:10px 16px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border-bottom:1px solid rgba(59,130,246,0.10)">
            <p class="sec-label">Ringkasan</p>
        </div>
        @foreach([
            ['label' => 'Kendaraan terdaftar',    'val' => $vehicleCount],
            ['label' => 'Total catatan service',   'val' => $serviceCount],
            ['label' => 'Sparepart dipantau',      'val' => $partCount],
        ] as $i => $item)
        <div style="padding:13px 16px;display:flex;align-items:center;justify-content:space-between;{{ $i < 2 ? 'border-bottom:1px solid rgba(59,130,246,0.07)' : '' }}">
            <p style="font-size:13px;color:#2D4B78;font-weight:500">{{ $item['label'] }}</p>
            <p style="font-size:14px;font-weight:800;color:#2563EB">{{ $item['val'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tips Perawatan (accordion) --}}
    <div class="card afu d1" style="overflow:hidden">
        <button onclick="toggleTips()" id="tips-btn"
                style="width:100%;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;background:none;border:none;cursor:pointer;border-bottom:0">
            <div style="display:flex;align-items:center;gap:10px">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center">
                    <i data-lucide="info" style="width:15px;height:15px;color:#2563EB"></i>
                </div>
                <p style="font-size:14px;font-weight:700;color:#0D1F3C;letter-spacing:-.1px">Tips Perawatan</p>
            </div>
            <i data-lucide="chevron-right" id="tips-chevron" style="width:16px;height:16px;color:#6B8DB8;transition:transform .2s"></i>
        </button>

        <div id="tips-content" style="display:none">
            @foreach($tips as $i => $tip)
            <div style="padding:13px 16px;border-top:1px solid rgba(59,130,246,0.07)">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px">
                    <p style="font-size:13px;font-weight:700;color:#0D1F3C;letter-spacing:-.1px">{{ $tip['title'] }}</p>
                    <span style="font-size:10px;font-weight:700;color:#2563EB;background:rgba(37,99,235,0.10);padding:2px 8px;border-radius:99px;flex-shrink:0;border:1px solid rgba(37,99,235,0.15)">{{ $tip['tag'] }}</span>
                </div>
                <p style="font-size:12px;color:#6B8DB8;margin-top:4px;line-height:1.6;font-weight:500">{{ $tip['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Menu --}}
    <div class="card afu d2" style="overflow:hidden">
        <a href="{{ route('notification') }}" style="width:100%;padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid rgba(59,130,246,0.07);text-decoration:none">
            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i data-lucide="bell" style="width:15px;height:15px;color:#2563EB"></i>
            </div>
            <div style="flex:1;text-align:left">
                <p style="font-size:13px;font-weight:700;color:#0D1F3C;letter-spacing:-.1px">Notifikasi</p>
                <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Atur reminder service</p>
            </div>
            <i data-lucide="chevron-right" style="width:15px;height:15px;color:#6B8DB8"></i>
        </a>
        <a href="{{ route('security') }}" style="width:100%;padding:14px 16px;display:flex;align-items:center;gap:12px;text-decoration:none">
            <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i data-lucide="shield" style="width:15px;height:15px;color:#2563EB"></i>
            </div>
            <div style="flex:1;text-align:left">
                <p style="font-size:13px;font-weight:700;color:#0D1F3C;letter-spacing:-.1px">Keamanan</p>
                <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Password & sesi login</p>
            </div>
            <i data-lucide="chevron-right" style="width:15px;height:15px;color:#6B8DB8"></i>
        </a>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="tap"
                style="width:100%;padding:14px 16px;display:flex;align-items:center;gap:12px;background:rgba(254,226,226,0.60);border:1px solid rgba(220,38,38,0.15);border-radius:16px;cursor:pointer">
            <div style="width:32px;height:32px;border-radius:9px;background:rgba(220,38,38,0.10);border:1px solid rgba(220,38,38,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i data-lucide="log-out" style="width:15px;height:15px;color:#DC2626"></i>
            </div>
            <span style="font-size:14px;font-weight:700;color:#DC2626;letter-spacing:-.1px">Keluar dari akun</span>
        </button>
    </form>

    <p style="text-align:center;font-size:11px;color:#6B8DB8;padding-bottom:8px;font-weight:500">MBG v1.0 · Gratis &amp; Aman</p>

</div>

@push('scripts')
<script>
    let tipsOpen = false;
    function toggleTips() {
        tipsOpen = !tipsOpen;
        const content = document.getElementById('tips-content');
        const chevron = document.getElementById('tips-chevron');
        const btn     = document.getElementById('tips-btn');
        content.style.display    = tipsOpen ? 'block' : 'none';
        chevron.style.transform  = tipsOpen ? 'rotate(90deg)' : '';
        btn.style.borderBottom   = tipsOpen ? '1px solid rgba(59,130,246,0.08)' : 'none';
    }
</script>
@endpush

@endsection