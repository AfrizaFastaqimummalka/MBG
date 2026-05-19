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
        <h1 style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-.4px">Notifikasi</h1>
        <p style="font-size:12px;color:rgba(255,255,255,0.65);margin-top:2px">Pengaturan reminder via WhatsApp</p>
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
    @if(session('error'))
    <div style="background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.20);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:8px">
        <i data-lucide="alert-circle" style="width:16px;height:16px;color:#DC2626;flex-shrink:0"></i>
        <p style="font-size:13px;color:#DC2626;font-weight:600">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Info Fonnte --}}
    <div style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid rgba(59,130,246,0.20);border-radius:14px;padding:14px 16px;display:flex;gap:10px">
        <i data-lucide="info" style="width:16px;height:16px;color:#2563EB;flex-shrink:0;margin-top:1px"></i>
        <p style="font-size:12px;color:#1D4ED8;line-height:1.6;font-weight:500">
            Notifikasi dikirim via <strong>WhatsApp</strong> menggunakan layanan <strong>Fonnte</strong>.
            Pastikan token Fonnte sudah diisi di file <code style="background:rgba(37,99,235,0.10);padding:1px 5px;border-radius:4px">.env</code>.
        </p>
    </div>

    {{-- Form Pengaturan --}}
    <form method="POST" action="{{ route('notification.update') }}">
        @csrf
        @method('PUT')

        {{-- Nomor WhatsApp --}}
        <div class="card afu" style="overflow:hidden;margin-bottom:12px">
            <div style="padding:10px 16px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border-bottom:1px solid rgba(59,130,246,0.10)">
                <p class="sec-label">Nomor WhatsApp Tujuan</p>
            </div>
            <div style="padding:16px">
                <label style="font-size:12px;font-weight:600;color:#2D4B78;display:block;margin-bottom:6px">Nomor HP (format: 628xxxxxxxx)</label>
                <div style="display:flex;align-items:center;gap:8px;background:#F8FAFF;border:1.5px solid {{ $errors->has('wa_phone') ? '#DC2626' : 'rgba(59,130,246,0.20)' }};border-radius:10px;padding:10px 12px">
                    <i data-lucide="smartphone" style="width:15px;height:15px;color:#6B8DB8;flex-shrink:0"></i>
                    <input type="tel" name="wa_phone"
                           value="{{ old('wa_phone', $setting->wa_phone ?? $user->phone) }}"
                           placeholder="628123456789"
                           style="flex:1;border:none;background:none;font-size:14px;color:#0D1F3C;font-weight:600;outline:none">
                </div>
                @error('wa_phone')
                <p style="font-size:11px;color:#DC2626;margin-top:4px">{{ $message }}</p>
                @enderror
                <p style="font-size:11px;color:#6B8DB8;margin-top:6px;font-weight:500">Kosongkan jika ingin pakai nomor yang sama dengan profil akun.</p>
            </div>
        </div>

        {{-- Toggle Notifikasi --}}
        <div class="card afu" style="overflow:hidden;margin-bottom:12px">
            <div style="padding:10px 16px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border-bottom:1px solid rgba(59,130,246,0.10)">
                <p class="sec-label">Jenis Notifikasi</p>
            </div>

            {{-- Reminder Service --}}
            <div style="padding:14px 16px;border-bottom:1px solid rgba(59,130,246,0.07);display:flex;align-items:center;justify-content:space-between;gap:12px">
                <div>
                    <p style="font-size:13px;font-weight:700;color:#0D1F3C">Reminder Service</p>
                    <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Pengingat jadwal servis kendaraan</p>
                </div>
                <label style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                    <input type="checkbox" name="wa_service_reminder" value="1"
                           {{ old('wa_service_reminder', $setting->wa_service_reminder) ? 'checked' : '' }}
                           style="opacity:0;width:0;height:0">
                    <span onclick="this.previousElementSibling.click();toggleSwitch(this)"
                          style="position:absolute;cursor:pointer;inset:0;border-radius:99px;transition:.2s;background:{{ $setting->wa_service_reminder ? '#2563EB' : '#CBD5E1' }}">
                        <span style="position:absolute;left:{{ $setting->wa_service_reminder ? '22px' : '2px' }};top:2px;width:20px;height:20px;border-radius:50%;background:#fff;transition:.2s;box-shadow:0 1px 4px rgba(0,0,0,0.15)"></span>
                    </span>
                </label>
            </div>

            {{-- Alert Sparepart --}}
            <div style="padding:14px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px">
                <div>
                    <p style="font-size:13px;font-weight:700;color:#0D1F3C">Alert Sparepart</p>
                    <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Notifikasi saat sparepart mendekati batas</p>
                </div>
                <label style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                    <input type="checkbox" name="wa_sparepart_alert" value="1"
                           {{ old('wa_sparepart_alert', $setting->wa_sparepart_alert) ? 'checked' : '' }}
                           style="opacity:0;width:0;height:0">
                    <span onclick="this.previousElementSibling.click();toggleSwitch(this)"
                          style="position:absolute;cursor:pointer;inset:0;border-radius:99px;transition:.2s;background:{{ $setting->wa_sparepart_alert ? '#2563EB' : '#CBD5E1' }}">
                        <span style="position:absolute;left:{{ $setting->wa_sparepart_alert ? '22px' : '2px' }};top:2px;width:20px;height:20px;border-radius:50%;background:#fff;transition:.2s;box-shadow:0 1px 4px rgba(0,0,0,0.15)"></span>
                    </span>
                </label>
            </div>
        </div>

        <button type="submit"
                style="width:100%;padding:14px;background:linear-gradient(135deg,#2563EB,#1D4ED8);border:none;border-radius:14px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
            <i data-lucide="save" style="width:16px;height:16px"></i>
            Simpan Pengaturan
        </button>
    </form>

    {{-- Test Kirim --}}
    <form method="POST" action="{{ route('notification.test') }}">
        @csrf
        <button type="submit"
                style="width:100%;padding:14px;background:#fff;border:1.5px solid rgba(59,130,246,0.30);border-radius:14px;color:#2563EB;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px">
            <i data-lucide="send" style="width:16px;height:16px"></i>
            Kirim Pesan Test WhatsApp
        </button>
    </form>

</div>

@push('scripts')
<script>
function toggleSwitch(span) {
    const isChecked = span.previousElementSibling.checked; // sudah di-click oleh onclick sebelum ini
    span.style.background = isChecked ? '#2563EB' : '#CBD5E1';
    span.querySelector('span').style.left = isChecked ? '22px' : '2px';
}
</script>
@endpush

@endsection
