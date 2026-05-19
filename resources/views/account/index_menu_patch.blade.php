{{-- Ganti bagian @foreach menu di account/index.blade.php --}}
{{-- CARI baris ini: --}}
{{--
    @foreach([
        ['icon' => 'bell',   'label' => 'Notifikasi', 'sub' => 'Atur reminder service'],
        ['icon' => 'shield', 'label' => 'Keamanan',   'sub' => 'Password & sesi login'],
    ] as $i => $item)
    <button style="...">
--}}

{{-- GANTI DENGAN ini: --}}
<a href="{{ route('notification') }}" style="width:100%;padding:14px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid rgba(59,130,246,0.07);background:none;text-decoration:none">
    <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <i data-lucide="bell" style="width:15px;height:15px;color:#2563EB"></i>
    </div>
    <div style="flex:1;text-align:left">
        <p style="font-size:13px;font-weight:700;color:#0D1F3C;letter-spacing:-.1px">Notifikasi</p>
        <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Atur reminder service</p>
    </div>
    <i data-lucide="chevron-right" style="width:15px;height:15px;color:#6B8DB8"></i>
</a>
<a href="{{ route('security') }}" style="width:100%;padding:14px 16px;display:flex;align-items:center;gap:12px;background:none;text-decoration:none">
    <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <i data-lucide="shield" style="width:15px;height:15px;color:#2563EB"></i>
    </div>
    <div style="flex:1;text-align:left">
        <p style="font-size:13px;font-weight:700;color:#0D1F3C;letter-spacing:-.1px">Keamanan</p>
        <p style="font-size:11px;color:#6B8DB8;margin-top:2px;font-weight:500">Password & sesi login</p>
    </div>
    <i data-lucide="chevron-right" style="width:15px;height:15px;color:#6B8DB8"></i>
</a>
