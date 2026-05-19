@extends('layouts.app')

@section('content')
@php use App\Helpers\FormatHelper as F; @endphp

{{-- ─── HERO HEADER ──────────────────────────────────────────────────────── --}}
<div class="hero">
    <div class="hero-circle" style="top:-40px;right:-30px;width:160px;height:160px"></div>
    <div class="hero-circle" style="bottom:-60px;left:-20px;width:200px;height:200px;background:rgba(255,255,255,0.04)"></div>
    <div class="hero-circle" style="top:30px;right:60px;width:80px;height:80px;background:rgba(255,255,255,0.05)"></div>

    {{-- User row --}}
    <div style="display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1" class="afu">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:44px;height:44px;border-radius:14px;background:rgba(255,255,255,0.20);backdrop-filter:blur(12px);border:1.5px solid rgba(255,255,255,0.30);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <span style="font-size:15px;font-weight:800;color:#fff;letter-spacing:.5px">
                    {{ F::initials(auth()->user()->name) }}
                </span>
            </div>
            <div>
                <p style="font-size:11px;color:rgba(255,255,255,0.70);font-weight:600;letter-spacing:.07em;text-transform:uppercase">Selamat datang</p>
                <p style="font-size:18px;font-weight:800;color:#fff;letter-spacing:-.4px;margin-top:2px">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <div style="display:flex;gap:8px">
            <button class="icon-btn tap" title="Notifikasi">
                <i data-lucide="bell" style="width:16px;height:16px;color:rgba(255,255,255,0.90)"></i>
            </button>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="icon-btn tap" title="Keluar">
                    <i data-lucide="log-out" style="width:15px;height:15px;color:rgba(255,255,255,0.90)"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Stats row --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:20px;position:relative;z-index:1" class="afu d1">
        @php $needAttn = $needAttention; @endphp
        @foreach([
            ['label' => 'Kendaraan', 'val' => $vehicles->count(), 'red' => false],
            ['label' => 'Perhatian',  'val' => $needAttention, 'red' => $needAttention > 0],
            ['label' => 'Biaya '.$year, 'val' => F::rp($totalCostYear, true), 'red' => false],
            ['label' => 'Riwayat',   'val' => $historyCount, 'red' => false],
        ] as $stat)
        <div style="background:{{ $stat['red'] ? 'rgba(254,226,226,0.20)' : 'rgba(255,255,255,0.14)' }};backdrop-filter:blur(8px);border:1px solid {{ $stat['red'] ? 'rgba(252,165,165,0.35)' : 'rgba(255,255,255,0.20)' }};border-radius:12px;padding:10px 8px;text-align:center">
            <p style="font-size:16px;font-weight:800;color:{{ $stat['red'] ? '#FCA5A5' : '#fff' }};letter-spacing:-.5px;line-height:1">{{ $stat['val'] }}</p>
            <p style="font-size:10px;color:{{ $stat['red'] ? 'rgba(252,165,165,0.85)' : 'rgba(255,255,255,0.65)' }};margin-top:4px;font-weight:600">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- ─── MAIN CONTENT ─────────────────────────────────────────────────────── --}}
<div style="padding:16px 16px 0;display:flex;flex-direction:column;gap:14px">

    {{-- Section: Kendaraan --}}
    <div class="afu">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
            <p class="sec-label">Kendaraan</p>
            <button class="btn-sm tap" onclick="openSheet('sheet-vehicle')">
                <i data-lucide="plus" style="width:13px;height:13px"></i>
                Tambah
            </button>
        </div>

        @if($vehicles->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i data-lucide="car" style="width:22px;height:22px;color:#6B8DB8"></i>
                    </div>
                    <p class="empty-title">Belum ada kendaraan</p>
                    <p class="empty-sub">Tambahkan kendaraan untuk mulai memantau jadwal service</p>
                    <button class="btn-sm tap" onclick="openSheet('sheet-vehicle')" style="margin-top:20px">
                        + Tambah Kendaraan
                    </button>
                </div>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:8px">
                @foreach($vehicles as $i => $v)
                @php $m = F::statusMeta($v->status); @endphp
                <div class="card tap" style="padding:14px 16px;display:flex;align-items:center;gap:12px;animation:fadeUp .3s ease both;animation-delay:{{ $i * 0.05 }}s">
                    <div style="width:40px;height:40px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border:1px solid rgba(59,130,246,0.18);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i data-lucide="car" style="width:17px;height:17px;color:#2563EB;stroke-width:2"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-size:14px;font-weight:700;color:#0D1F3C;letter-spacing:-.2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $v->name }}</p>
                        <p style="font-size:12px;color:#6B8DB8;margin-top:2px;font-weight:500">
                            {{ $v->type }} · {{ $v->owner ?? '–' }} · {{ number_format($v->odometer) }} km
                        </p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0">
                        <span class="pill" style="background:{{ $m['bg'] }};color:{{ $m['color'] }};border:1px solid {{ $m['border'] }}">
                            {{ $m['label'] }}
                        </span>
                        @if($v->next_service_date)
                            <p style="font-size:10px;color:#6B8DB8;font-weight:500">{{ F::fd($v->next_service_date->format('Y-m-d'), true) }}</p>
                        @endif
                    </div>
                    {{-- Delete vehicle --}}
                    <form method="POST" action="{{ route('vehicles.destroy', $v->id) }}" onsubmit="return confirm('Hapus kendaraan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;cursor:pointer;opacity:.4;padding:4px">
                            <i data-lucide="trash-2" style="width:13px;height:13px;color:#DC2626"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Section: Jadwal Service --}}
    @if($vehicles->isNotEmpty())
    <div class="afu d1">
        <p class="sec-label" style="margin-bottom:10px">Jadwal Service</p>
        <div class="card" style="overflow:hidden">
            {{-- Legend --}}
            <div style="padding:10px 16px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border-bottom:1px solid rgba(59,130,246,0.12);display:flex;flex-wrap:wrap;gap:8px 20px">
                @foreach(['overdue'=>['Terlambat','#DC2626'],'urgent'=>['Mendesak','#D97706'],'upcoming'=>['Segera','#2563EB'],'ok'=>['Aman','#059669']] as $s=>[$label,$dot])
                <div style="display:flex;align-items:center;gap:5px">
                    <div style="width:7px;height:7px;border-radius:99px;background:{{ $dot }}"></div>
                    <span style="font-size:11px;color:#6B8DB8;font-weight:600">{{ $label }}</span>
                </div>
                @endforeach
            </div>
            {{-- List --}}
            @foreach($vehicles as $i => $v)
            @php $m = F::statusMeta($v->status); $kmLeft = $v->next_service_km ? $v->next_service_km - $v->odometer : null; @endphp
            <div style="padding:12px 16px;display:flex;align-items:center;gap:10px{{ $i < $vehicles->count()-1 ? ';border-bottom:1px solid rgba(59,130,246,0.08)' : '' }}">
                <div style="width:8px;height:8px;border-radius:99px;background:{{ $m['dot'] }};flex-shrink:0"></div>
                <div style="flex:1;min-width:0">
                    <p style="font-size:13px;font-weight:700;color:#0D1F3C;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $v->name }}</p>
                    <div style="display:flex;gap:10px;margin-top:2px">
                        @if($v->next_service_date)
                        <span style="font-size:11px;color:#6B8DB8;font-weight:500">{{ F::relDate($v->next_service_date->format('Y-m-d')) }}</span>
                        @endif
                        @if($kmLeft !== null)
                        <span style="font-size:11px;color:#6B8DB8;font-weight:500">
                            {{ $kmLeft > 0 ? number_format($kmLeft).' km lagi' : 'KM terlewat' }}
                        </span>
                        @endif
                    </div>
                </div>
                <span class="pill" style="background:{{ $m['bg'] }};color:{{ $m['color'] }};border:1px solid {{ $m['border'] }};flex-shrink:0">
                    {{ $m['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>{{-- /content --}}

@push('sheets')
{{-- ─── SHEET: TAMBAH KENDARAAN ─────────────────────────────────────────── --}}
<div class="sheet-overlay" id="sheet-vehicle" style="display:none">
    <div class="sheet-backdrop" onclick="closeSheet('sheet-vehicle')"></div>
    <div class="sheet-panel asu">
        <div class="sheet-handle"><div class="sheet-handle-bar"></div></div>
        <div class="sheet-header">
            <span>Tambah Kendaraan</span>
            <button class="sheet-close tap" onclick="closeSheet('sheet-vehicle')">
                <i data-lucide="x" style="width:15px;height:15px;color:#2D4B78"></i>
            </button>
        </div>
        <div class="sheet-body">
            <form method="POST" action="{{ route('vehicles.store') }}" id="vehicle-form"
                  onsubmit="setLoading('vehicle-save-btn', true)"
                  style="display:flex;flex-direction:column;gap:14px">
                @csrf

                <div class="field">
                    <label>Nama Kendaraan *</label>
                    <input class="inp" type="text" name="name" placeholder="misal: NMAX NEO S" required>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label>Merk</label>
                        <input class="inp" type="text" name="brand" placeholder="Yamaha">
                    </div>
                    <div class="field">
                        <label>Tahun</label>
                        <input class="inp" type="number" name="year" placeholder="{{ date('Y') }}" min="1900" max="{{ date('Y')+1 }}">
                    </div>
                </div>

                <div class="field">
                    <label>Jenis</label>
                    <select class="inp" name="type">
                        @foreach(['Motor Matic','Motor Manual','Motor Sport','Mobil','Truk/Pickup'] as $t)
                            <option>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid2">
                    <div class="field">
                        <label>Plat Nomor</label>
                        <input class="inp" type="text" name="plate" placeholder="B 1234 AB">
                    </div>
                    <div class="field">
                        <label>Odometer (km)</label>
                        <input class="inp" type="number" name="odometer" placeholder="12500" min="0">
                    </div>
                </div>

                <div class="field">
                    <label>Pemilik</label>
                    <input class="inp" type="text" name="owner" placeholder="misal: Suami">
                </div>

                <div class="divider-light"></div>
                <p class="sec-label">Jadwal Service Berikutnya</p>

                <div class="grid2">
                    <div class="field">
                        <label>Tanggal</label>
                        <input class="inp" type="date" name="next_service_date">
                    </div>
                    <div class="field">
                        <label>Target KM</label>
                        <input class="inp" type="number" name="next_service_km" placeholder="13000" min="0">
                    </div>
                </div>

                <button type="submit" class="btn-primary tap" id="vehicle-save-btn">
                    <span>Simpan Kendaraan</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Buka sheet otomatis jika ada error dari form kendaraan
    @if($errors->hasAny(['name','brand','type','year','plate','odometer','owner','next_service_date','next_service_km']))
        openSheet('sheet-vehicle');
    @endif
</script>
@endpush

@endsection
