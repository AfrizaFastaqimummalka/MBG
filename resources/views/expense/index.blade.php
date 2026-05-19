@extends('layouts.app')

@section('content')
@php use App\Helpers\FormatHelper as F; @endphp

{{-- ─── HERO ─────────────────────────────────────────────────────────────── --}}
<div class="hero">
    <div class="hero-circle" style="top:-40px;right:-30px;width:160px;height:160px"></div>
    <div class="hero-circle" style="bottom:-60px;left:-20px;width:200px;height:200px;background:rgba(255,255,255,0.04)"></div>

    <div style="position:relative;z-index:1" class="afu">
        <p style="font-size:11px;color:rgba(255,255,255,0.65);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Rekap</p>
        <h1 style="font-size:22px;font-weight:800;color:#fff;letter-spacing:-.4px">Pengeluaran</h1>
    </div>
</div>

{{-- ─── CONTENT ──────────────────────────────────────────────────────────── --}}
<div style="padding:16px 16px 0;display:flex;flex-direction:column;gap:16px">

    {{-- Summary cards --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px" class="afu">
        @foreach([
            ['label' => 'Tahun '.$year, 'val' => F::rp($totalYear, true)],
            ['label' => 'Keseluruhan',  'val' => F::rp($totalAll,  true)],
        ] as $card)
        <div class="card" style="padding:18px 16px;text-align:center">
            <p style="font-size:11px;color:#6B8DB8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;font-weight:600">{{ $card['label'] }}</p>
            <p style="font-size:22px;font-weight:700;color:#0D1F3C;letter-spacing:-.04em">{{ $card['val'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Bar chart 6 bulan --}}
    <div class="card afu d1" style="padding:20px 18px 24px">
        <p style="font-size:12px;font-weight:600;color:#2D4B78;margin-bottom:20px;letter-spacing:.05em;text-transform:uppercase;text-align:center">6 Bulan Terakhir</p>
        @php
            $maxVal = collect($months)->max('val') ?: 1;
        @endphp
        <div style="display:flex;align-items:flex-end;gap:6px;height:120px">
            @foreach($months as $m)
            @php $barH = max($m['val'] > 0 ? ($m['val']/$maxVal)*94 : 2, $m['val']>0?4:2); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px">
                <div style="width:100%;border-radius:6px 6px 0 0;background:{{ $m['val']>0 ? '#0D1F3C' : 'rgba(59,130,246,0.15)' }};height:{{ $barH }}px;opacity:{{ $m['val']>0?1:0.4 }};transition:height .6s"></div>
                <span style="font-size:10px;color:#6B8DB8;font-weight:500">{{ $m['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Per kendaraan --}}
    @if($byVehicle->isNotEmpty())
    <div class="card afu d2" style="overflow:hidden">
        <div style="padding:10px 16px;background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);border-bottom:1px solid rgba(59,130,246,0.10)">
            <p class="sec-label">Per Kendaraan</p>
        </div>
        @foreach($byVehicle as $i => $item)
        <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;{{ $i < $byVehicle->count()-1 ? 'border-bottom:1px solid rgba(59,130,246,0.07)' : '' }}">
            <div style="flex:1;margin-right:12px">
                <p style="font-size:13px;font-weight:500;color:#0D1F3C">{{ $item['name'] }}</p>
                <div style="margin-top:6px;border-radius:99px;overflow:hidden;height:4px;background:#EFF6FF">
                    <div style="width:{{ $item['pct'] }}%;height:100%;background:#0D1F3C;border-radius:99px"></div>
                </div>
            </div>
            <p style="font-size:16px;font-weight:600;color:#0D1F3C;flex-shrink:0">{{ F::rp($item['total'], true) }}</p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Daftar transaksi --}}
    @if($services->isNotEmpty())
    <div class="afu d3">
        <p class="sec-label" style="margin-bottom:10px">Transaksi</p>
        <div class="card" style="overflow:hidden">
            @foreach($services as $i => $s)
            <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;{{ $i < $services->count()-1 ? 'border-bottom:1px solid rgba(59,130,246,0.07)' : '' }}">
                <div>
                    <p style="font-size:13px;font-weight:500;color:#0D1F3C">{{ $s->type }}</p>
                    <p style="font-size:11px;color:#6B8DB8;margin-top:2px">
                        {{ F::fd($s->date->format('Y-m-d'), true) }} · {{ $s->vehicle->name ?? '–' }}
                    </p>
                </div>
                <p style="font-size:13px;font-weight:600;color:#0D1F3C">{{ F::rp($s->cost, true) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon">
                <i data-lucide="wallet" style="width:22px;height:22px;color:#6B8DB8"></i>
            </div>
            <p class="empty-title">Belum ada data pengeluaran</p>
            <p class="empty-sub">Catat service kendaraan untuk mulai merekap pengeluaran</p>
        </div>
    </div>
    @endif

</div>

@endsection
