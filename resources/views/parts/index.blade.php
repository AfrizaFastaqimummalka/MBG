@extends('layouts.app')

@section('content')
@php use App\Helpers\FormatHelper as F; @endphp

{{-- ─── HERO ─────────────────────────────────────────────────────────────── --}}
<div class="hero">
    <div class="hero-circle" style="top:-40px;right:-30px;width:160px;height:160px"></div>
    <div class="hero-circle" style="bottom:-50px;left:-10px;width:180px;height:180px;background:rgba(255,255,255,0.04)"></div>

    <div style="display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1" class="afu">
        <div>
            <p style="font-size:11px;color:rgba(255,255,255,0.65);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Pantau</p>
            <h1 style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-.4px">Sparepart</h1>
        </div>
        <button class="btn-ghost-white tap" onclick="openSheet('sheet-part')">
            <i data-lucide="plus" style="width:14px;height:14px;color:#fff"></i>
            Tambah
        </button>
    </div>
</div>

{{-- ─── LIST ─────────────────────────────────────────────────────────────── --}}
<div style="padding:14px 16px 0;display:flex;flex-direction:column;gap:10px">

    @if($parts->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">
                    <i data-lucide="settings-2" style="width:22px;height:22px;color:#6B8DB8"></i>
                </div>
                <p class="empty-title">Belum ada sparepart</p>
                <p class="empty-sub">Tambah sparepart untuk memantau masa pakai komponen kendaraan</p>
                <button class="btn-sm tap" onclick="openSheet('sheet-part')" style="margin-top:20px">
                    + Tambah Part
                </button>
            </div>
        </div>
    @else
        @foreach($parts as $i => $p)
        @php
            $m       = $p->statusMeta;
            $pct     = round($p->pct);
            $barColor = match($p->status) {
                'overdue'  => '#DC2626',
                'urgent'   => '#D97706',
                'upcoming' => '#2563EB',
                default    => '#111827',
            };
        @endphp
        <div class="card" style="padding:14px 16px;animation:fadeUp .3s ease both;animation-delay:{{ $i*0.04 }}s">
            {{-- Header row --}}
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:12px">
                <div style="flex:1;min-width:0">
                    <p style="font-size:14px;font-weight:600;color:#0D1F3C;letter-spacing:-.02em">{{ $p->name }}</p>
                    <p style="font-size:12px;color:#6B8DB8;margin-top:2px;font-weight:500">
                        {{ $p->vehicle->name ?? '–' }}
                        @if($p->installed_date)
                         · Dipasang {{ F::fd($p->installed_date->format('Y-m-d'), true) }}
                        @endif
                    </p>
                </div>
                <div style="display:flex;align-items:flex-start;gap:8px;flex-shrink:0">
                    <span class="pill" style="background:{{ $m['bg'] }};color:{{ $m['color'] }};border:1px solid {{ $m['border'] }}">
                        {{ $m['label'] }}
                    </span>
                    <form method="POST" action="{{ route('spareparts.destroy', $p->id) }}" onsubmit="return confirm('Hapus sparepart ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="padding:4px;background:none;border:none;cursor:pointer;opacity:.55">
                            <i data-lucide="trash-2" style="width:13px;height:13px;color:#6B8DB8"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Progress bar --}}
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <span style="font-size:11px;color:#6B8DB8;font-weight:500">Estimasi umur: {{ $p->lifespan }} {{ $p->unit }}</span>
                    <span style="font-size:11px;color:#2D4B78;font-weight:600">{{ $pct }}%</span>
                </div>
                <div style="height:4px;background:#EFF6FF;border-radius:99px;overflow:hidden">
                    <div style="width:{{ $pct }}%;height:100%;background:{{ $barColor }};border-radius:99px;transition:width .6s cubic-bezier(.16,1,.3,1)"></div>
                </div>
            </div>

            {{-- Price --}}
            @if($p->price)
            <p style="font-size:11px;color:#6B8DB8;margin-top:10px;font-weight:500">{{ F::rp($p->price) }}</p>
            @endif
        </div>
        @endforeach
    @endif

</div>

@push('sheets')
{{-- ─── SHEET: TAMBAH SPAREPART ──────────────────────────────────────────── --}}
<div class="sheet-overlay" id="sheet-part" style="display:none">
    <div class="sheet-backdrop" onclick="closeSheet('sheet-part')"></div>
    <div class="sheet-panel asu">
        <div class="sheet-handle"><div class="sheet-handle-bar"></div></div>
        <div class="sheet-header">
            <span>Tambah Sparepart</span>
            <button class="sheet-close tap" onclick="closeSheet('sheet-part')">
                <i data-lucide="x" style="width:15px;height:15px;color:#2D4B78"></i>
            </button>
        </div>
        <div class="sheet-body">
            <form method="POST" action="{{ route('spareparts.store') }}" id="part-form"
                  onsubmit="setLoading('part-save-btn', true)"
                  style="display:flex;flex-direction:column;gap:14px">
                @csrf

                <div class="field">
                    <label>Kendaraan *</label>
                    <select class="inp" name="vehicle_id" required>
                        <option value="">Pilih kendaraan...</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Nama Part *</label>
                    <input class="inp" type="text" name="name" placeholder="contoh: Oli Mesin, Ban Depan" required>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label>Harga (Rp)</label>
                        <input class="inp" type="number" name="price" placeholder="85000" min="0">
                    </div>
                    <div class="field">
                        <label>Tanggal Pasang</label>
                        <input class="inp" type="date" name="installed_date" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label>Umur Pakai</label>
                        <input class="inp" type="number" name="lifespan" placeholder="6" min="1">
                    </div>
                    <div class="field">
                        <label>Satuan</label>
                        <select class="inp" name="unit">
                            <option value="bulan">Bulan</option>
                            <option value="hari">Hari</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-primary tap" id="part-save-btn">
                    <span>Simpan Part</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    @if($errors->hasAny(['vehicle_id','name','price','installed_date','lifespan','unit']))
        openSheet('sheet-part');
    @endif
</script>
@endpush

@endsection
