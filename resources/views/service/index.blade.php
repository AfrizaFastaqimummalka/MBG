@extends('layouts.app')

@section('content')
@php use App\Helpers\FormatHelper as F; @endphp

{{-- ─── HERO ─────────────────────────────────────────────────────────────── --}}
<div class="hero">
    <div class="hero-circle" style="top:-40px;right:-30px;width:160px;height:160px"></div>
    <div class="hero-circle" style="bottom:-50px;left:-10px;width:180px;height:180px;background:rgba(255,255,255,0.04)"></div>

    <div style="display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1" class="afu">
        <div>
            <p style="font-size:11px;color:rgba(255,255,255,0.65);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Riwayat</p>
            <h1 style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-.4px">Service</h1>
        </div>
        <button class="btn-ghost-white tap" onclick="openSheet('sheet-service')">
            <i data-lucide="plus" style="width:14px;height:14px;color:#fff"></i>
            Catat
        </button>
    </div>
</div>

{{-- ─── LIST ─────────────────────────────────────────────────────────────── --}}
<div style="padding:14px 16px 0;display:flex;flex-direction:column;gap:10px">

    @if($services->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">
                    <i data-lucide="wrench" style="width:22px;height:22px;color:#6B8DB8"></i>
                </div>
                <p class="empty-title">Belum ada catatan service</p>
                <p class="empty-sub">Catat service pertama kamu untuk mulai memantau riwayat perawatan</p>
                <button class="btn-sm tap" onclick="openSheet('sheet-service')" style="margin-top:20px">
                    + Catat Service
                </button>
            </div>
        </div>
    @else
        @foreach($services as $i => $s)
        @php $hasDetail = $s->notes || $s->next_date || $s->next_km; @endphp
        <div class="card" style="overflow:hidden;animation:fadeUp .3s ease both;animation-delay:{{ $i*0.04 }}s" id="scard-{{ $s->id }}">
            {{-- Main content --}}
            <div style="padding:14px 16px">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px">
                    <div style="flex:1;min-width:0">
                        <p style="font-size:14px;font-weight:600;color:#0D1F3C;letter-spacing:-.02em;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $s->type }}</p>
                        <p style="font-size:12px;color:#6B8DB8;margin-top:2px">{{ $s->vehicle->name ?? '–' }}</p>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <p style="font-size:14px;font-weight:600;color:#0D1F3C">{{ F::rp($s->cost) }}</p>
                        <p style="font-size:11px;color:#6B8DB8;margin-top:2px">{{ F::fd($s->date->format('Y-m-d')) }}</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:16px;margin-top:10px">
                    @if($s->workshop)
                    <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#6B8DB8;font-weight:500">
                        <i data-lucide="map-pin" style="width:10px;height:10px"></i>
                        {{ $s->workshop }}
                    </span>
                    @endif
                    @if($s->odometer)
                    <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:#6B8DB8;font-weight:500">
                        <i data-lucide="gauge" style="width:10px;height:10px"></i>
                        {{ number_format($s->odometer) }} km
                    </span>
                    @endif
                </div>
            </div>

            @if($hasDetail)
            {{-- Toggle button --}}
            <button onclick="toggleDetail('{{ $s->id }}')"
                    id="toggle-{{ $s->id }}"
                    style="width:100%;display:flex;align-items:center;justify-content:center;gap:4px;padding:8px;border-top:1px solid rgba(59,130,246,0.10);background:#EFF6FF;border:none;border-top:1px solid rgba(59,130,246,0.10);cursor:pointer">
                <span id="toggle-label-{{ $s->id }}" style="font-size:11px;color:#6B8DB8;font-weight:600">Lihat detail</span>
                <i data-lucide="chevron-down" id="toggle-chevron-{{ $s->id }}" style="width:12px;height:12px;color:#6B8DB8;transition:transform .2s"></i>
            </button>

            {{-- Detail panel --}}
            <div id="detail-{{ $s->id }}" style="display:none;padding:12px 16px 14px;border-top:1px solid rgba(59,130,246,0.10)">
                @if($s->notes)
                    <p style="font-size:12px;color:#2D4B78;line-height:1.6;margin-bottom:8px">{{ $s->notes }}</p>
                @endif
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px">
                    @if($s->next_date)
                    <span style="font-size:11px;color:#065F46;background:#ECFDF5;border:1px solid #A7F3D0;padding:3px 9px;border-radius:99px;font-weight:600">
                        Next: {{ F::fd($s->next_date->format('Y-m-d')) }}
                    </span>
                    @endif
                    @if($s->next_km)
                    <span style="font-size:11px;color:#1E40AF;background:#EFF6FF;border:1px solid #BFDBFE;padding:3px 9px;border-radius:99px;font-weight:600">
                        {{ number_format($s->next_km) }} km
                    </span>
                    @endif
                </div>
                <form method="POST" action="{{ route('services.destroy', $s->id) }}" onsubmit="return confirm('Hapus catatan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <i data-lucide="trash-2" style="width:12px;height:12px"></i>
                        Hapus catatan
                    </button>
                </form>
            </div>

            @else
            {{-- Hapus langsung jika tidak ada detail --}}
            <form method="POST" action="{{ route('services.destroy', $s->id) }}" onsubmit="return confirm('Hapus catatan ini?')"
                  style="border-top:1px solid rgba(59,130,246,0.10)">
                @csrf @method('DELETE')
                <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:4px;padding:8px;background:#EFF6FF;border:none;cursor:pointer">
                    <i data-lucide="trash-2" style="width:12px;height:12px;color:#DC2626"></i>
                    <span style="font-size:11px;color:#DC2626;font-weight:600">Hapus</span>
                </button>
            </form>
            @endif
        </div>
        @endforeach
    @endif

</div>

@push('sheets')
{{-- ─── SHEET: CATAT SERVICE ─────────────────────────────────────────────── --}}
<div class="sheet-overlay" id="sheet-service" style="display:none">
    <div class="sheet-backdrop" onclick="closeSheet('sheet-service')"></div>
    <div class="sheet-panel asu">
        <div class="sheet-handle"><div class="sheet-handle-bar"></div></div>
        <div class="sheet-header">
            <span>Catat Service</span>
            <button class="sheet-close tap" onclick="closeSheet('sheet-service')">
                <i data-lucide="x" style="width:15px;height:15px;color:#2D4B78"></i>
            </button>
        </div>
        <div class="sheet-body">
            <form method="POST" action="{{ route('services.store') }}" id="service-form"
                  onsubmit="setLoading('service-save-btn', true)"
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

                <div class="grid2">
                    <div class="field">
                        <label>Tanggal</label>
                        <input class="inp" type="date" name="date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="field">
                        <label>Odometer (km)</label>
                        <input class="inp" type="number" name="odometer" placeholder="12500" min="0">
                    </div>
                </div>

                <div class="field">
                    <label>Jenis Service *</label>
                    <input class="inp" type="text" name="type" placeholder="contoh: Ganti Oli + Filter" required>
                </div>

                <div class="field">
                    <label>Bengkel</label>
                    <input class="inp" type="text" name="workshop" placeholder="nama bengkel">
                </div>

                <div class="field">
                    <label>Biaya (Rp)</label>
                    <input class="inp" type="number" name="cost" placeholder="350000" min="0">
                </div>

                <div class="field">
                    <label>Catatan</label>
                    <textarea class="inp" name="notes" rows="2" placeholder="catatan tambahan..."></textarea>
                </div>

                <div class="divider-light"></div>
                <p class="sec-label">Jadwal Service Berikutnya</p>

                <div class="grid2">
                    <div class="field">
                        <label>Tanggal</label>
                        <input class="inp" type="date" name="next_date">
                    </div>
                    <div class="field">
                        <label>Target KM</label>
                        <input class="inp" type="number" name="next_km" placeholder="13000" min="0">
                    </div>
                </div>

                <button type="submit" class="btn-primary tap" id="service-save-btn">
                    <span>Simpan Catatan</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function toggleDetail(id) {
        const panel   = document.getElementById('detail-' + id);
        const chevron = document.getElementById('toggle-chevron-' + id);
        const label   = document.getElementById('toggle-label-' + id);
        const isOpen  = panel.style.display !== 'none';
        panel.style.display   = isOpen ? 'none' : 'block';
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        label.textContent     = isOpen ? 'Lihat detail' : 'Sembunyikan';
    }

    @if($errors->hasAny(['vehicle_id','date','type','workshop','cost','notes','next_date','next_km']))
        openSheet('sheet-service');
    @endif
</script>
@endpush

@endsection
