<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'My Bike Garage') }}</title>

    <!-- Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons via CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─── Design tokens ─────────────────────────────────────── */
        :root {
            --bg-gradient   : linear-gradient(160deg, #EBF4FF 0%, #F5F9FF 50%, #EEF4FF 100%);
            --surface       : #FFFFFF;
            --surface-2     : #EFF6FF;
            --border        : rgba(59,130,246,0.14);
            --border-strong : rgba(59,130,246,0.28);
            --ink           : #0D1F3C;
            --ink-2         : #2D4B78;
            --ink-3         : #6B8DB8;
            --primary       : #2563EB;
            --primary-mid   : #1D4ED8;
            --primary-dark  : #1E40AF;
            --primary-light : #DBEAFE;
        }

        *, *::before, *::after { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

        html, body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background: var(--bg-gradient);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
            overscroll-behavior: none;
            height: 100%;
        }

        /* Hide scrollbar globally */
        * { scrollbar-width: none; }
        *::-webkit-scrollbar { display: none; }

        /* ─── App shell ─────────────────────────────────────────── */
        #app-shell {
            max-width: 480px;
            margin: 0 auto;
            min-height: 100dvh;
            position: relative;
        }

        /* ─── Animations ─────────────────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(100%); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .afu  { animation: fadeUp  .32s cubic-bezier(.16,1,.3,1) both; }
        .asu  { animation: slideUp .36s cubic-bezier(.16,1,.3,1) both; }
        .afi  { animation: fadeIn  .20s ease both; }
        .d1   { animation-delay: .06s; }
        .d2   { animation-delay: .12s; }
        .d3   { animation-delay: .18s; }
        .d4   { animation-delay: .24s; }

        /* ─── Tap feedback ───────────────────────────────────────── */
        .tap { cursor: pointer; transition: opacity .12s, transform .12s; }
        .tap:active { opacity: .72; transform: scale(.97); }

        /* ─── Form inputs ────────────────────────────────────────── */
        .inp {
            width: 100%;
            padding: 11px 14px;
            background: var(--surface);
            border: 1.5px solid rgba(59,130,246,0.18);
            border-radius: 12px;
            color: var(--ink);
            font-size: 15px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            -webkit-appearance: none;
            appearance: none;
        }
        .inp:focus { border-color: var(--primary); box-shadow: 0 0 0 3.5px rgba(37,99,235,0.12); }
        .inp::placeholder { color: var(--ink-3); font-weight: 400; }
        .inp.pl { padding-left: 42px; }
        textarea.inp { resize: none; line-height: 1.6; }
        select.inp {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B8DB8' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 13px center;
            padding-right: 38px;
        }

        /* ─── Buttons ────────────────────────────────────────────── */
        .btn-primary {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: #fff;
            border: none;
            border-radius: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -.2px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: box-shadow .2s, transform .12s;
            box-shadow: 0 2px 16px rgba(37,99,235,0.35);
        }
        .btn-primary:active { transform: scale(.98); }
        .btn-primary:disabled { opacity: .42; cursor: not-allowed; box-shadow: none; }

        .btn-sm {
            padding: 7px 14px;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 1px 8px rgba(37,99,235,0.28);
            transition: box-shadow .15s, transform .12s;
        }
        .btn-sm:active { transform: scale(.97); }

        .btn-ghost-white {
            padding: 7px 14px;
            background: rgba(255,255,255,0.20);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.30);
            border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(8px);
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #DC2626;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            padding: 0;
        }

        /* ─── Cards ──────────────────────────────────────────────── */
        .card {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(59,130,246,0.12);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(30,64,175,0.04), 0 4px 16px rgba(30,64,175,0.04);
        }

        /* ─── Pills ──────────────────────────────────────────────── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .02em;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ─── Labels ─────────────────────────────────────────────── */
        .sec-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--ink-3);
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        /* ─── Hero header ────────────────────────────────────────── */
        .hero {
            background: linear-gradient(150deg, #1D4ED8 0%, #2563EB 45%, #3B82F6 100%);
            padding: 56px 20px 24px;
            position: relative;
            overflow: hidden;
        }
        .hero-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: rgba(255,255,255,0.06);
        }

        /* ─── Bottom nav spacing ─────────────────────────────────── */
        .pb-nav { padding-bottom: calc(76px + env(safe-area-inset-bottom, 8px)); }

        /* ─── Bottom nav ─────────────────────────────────────────── */
        #bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            z-index: 50;
            background: rgba(255,255,255,0.90);
            backdrop-filter: blur(28px) saturate(200%);
            -webkit-backdrop-filter: blur(28px) saturate(200%);
            border-top: 1px solid rgba(37,99,235,0.10);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 8px 4px calc(10px + env(safe-area-inset-bottom, 0px));
            box-shadow: 0 -8px 32px rgba(30,64,175,0.07);
        }

        .nav-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 6px 18px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            background: transparent;
            transition: background .18s;
            text-decoration: none;
        }
        .nav-btn.active {
            background: linear-gradient(135deg, rgba(59,130,246,0.14) 0%, rgba(37,99,235,0.10) 100%);
            box-shadow: 0 1px 4px rgba(37,99,235,0.10);
        }
        .nav-icon { color: #6B8DB8; transition: color .18s, transform .18s; }
        .nav-btn.active .nav-icon { color: #2563EB; transform: translateY(-1px); }
        .nav-label { font-size: 10px; font-weight: 500; color: #6B8DB8; transition: color .18s; font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-btn.active .nav-label { font-weight: 800; color: #2563EB; }

        /* ─── Sheet / modal ──────────────────────────────────────── */
        .sheet-overlay {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            max-width: 480px;
            margin: 0 auto;
        }
        .sheet-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(17,17,16,0.45);
            backdrop-filter: blur(3px);
        }
        .sheet-panel {
            position: relative;
            width: 100%;
            background: var(--surface);
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -4px 40px rgba(0,0,0,0.12);
            max-height: 92dvh;
            display: flex;
            flex-direction: column;
        }
        .sheet-handle { display: flex; align-items: center; justify-content: center; padding: 12px 0 4px; flex-shrink: 0; }
        .sheet-handle-bar { width: 36px; height: 4px; border-radius: 2px; background: var(--border-strong); }
        .sheet-header { flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; padding: 10px 20px 12px; border-bottom: 1px solid var(--border); }
        .sheet-header span { font-size: 16px; font-weight: 700; color: var(--ink); letter-spacing: -.3px; }
        .sheet-close { width: 30px; height: 30px; background: var(--surface-2); border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .sheet-body { overflow-y: auto; flex: 1; padding: 16px 20px; padding-bottom: calc(32px + env(safe-area-inset-bottom, 0px)); }

        /* ─── Toast ──────────────────────────────────────────────── */
        #toast-container {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 200;
            width: calc(100% - 32px);
            max-width: min(400px, 480px);
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }
        .toast {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,.08);
            pointer-events: all;
            animation: fadeUp .25s cubic-bezier(.16,1,.3,1) both;
        }

        /* ─── Empty state ────────────────────────────────────────── */
        .empty-state { display: flex; flex-direction: column; align-items: center; padding: 48px 24px; text-align: center; }
        .empty-icon { width: 52px; height: 52px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .empty-title { font-size: 15px; font-weight: 600; color: var(--ink); letter-spacing: -.2px; }
        .empty-sub { font-size: 13px; color: var(--ink-3); margin-top: 6px; line-height: 1.6; max-width: 260px; }

        /* ─── Utility ─────────────────────────────────────────────── */
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field label { font-size: 12px; font-weight: 600; color: var(--ink-2); }
        .divider-light { height: 1px; background: rgba(59,130,246,0.12); margin: 4px 0; }
        .icon-btn { width: 38px; height: 38px; border-radius: 11px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    </style>
</head>
<body class="h-full">

<div id="app-shell">

    {{-- ─── Toast container ─────────────────────────────────── --}}
    <div id="toast-container"></div>

    {{-- ─── Page content ────────────────────────────────────── --}}
    <main class="pb-nav">
        @yield('content')
    </main>

    {{-- ─── Bottom navigation ───────────────────────────────── --}}
    <nav id="bottom-nav">
        @php $currentRoute = Route::currentRouteName(); @endphp

        <a href="{{ route('home') }}" class="nav-btn tap {{ $currentRoute === 'home' ? 'active' : '' }}">
            <div class="nav-icon"><i data-lucide="home" style="width:20px;height:20px;stroke-width:{{ $currentRoute==='home'?'2.3':'1.7' }}"></i></div>
            <span class="nav-label">Garasi</span>
        </a>

        <a href="{{ route('service') }}" class="nav-btn tap {{ $currentRoute === 'service' ? 'active' : '' }}">
            <div class="nav-icon"><i data-lucide="wrench" style="width:20px;height:20px;stroke-width:{{ $currentRoute==='service'?'2.3':'1.7' }}"></i></div>
            <span class="nav-label">Service</span>
        </a>

        <a href="{{ route('expense') }}" class="nav-btn tap {{ $currentRoute === 'expense' ? 'active' : '' }}">
            <div class="nav-icon"><i data-lucide="wallet" style="width:20px;height:20px;stroke-width:{{ $currentRoute==='expense'?'2.3':'1.7' }}"></i></div>
            <span class="nav-label">Biaya</span>
        </a>

        <a href="{{ route('parts') }}" class="nav-btn tap {{ $currentRoute === 'parts' ? 'active' : '' }}">
            <div class="nav-icon"><i data-lucide="settings-2" style="width:20px;height:20px;stroke-width:{{ $currentRoute==='parts'?'2.3':'1.7' }}"></i></div>
            <span class="nav-label">Part</span>
        </a>

        <a href="{{ route('account') }}" class="nav-btn tap {{ $currentRoute === 'account' ? 'active' : '' }}">
            <div class="nav-icon"><i data-lucide="user" style="width:20px;height:20px;stroke-width:{{ $currentRoute==='account'?'2.3':'1.7' }}"></i></div>
            <span class="nav-label">Akun</span>
        </a>
    </nav>

    {{-- ─── Shared sheets & modals ──────────────────────────── --}}
    @stack('sheets')

</div>{{-- /app-shell --}}

<script>
    // ─── Init Lucide icons ─────────────────────────────────────────────────────
    lucide.createIcons();

    // ─── Toast sistem ──────────────────────────────────────────────────────────
    function showToast(msg, type = 'info') {
        const cfg = {
            ok:   { bg: '#ECFDF5', border: '#A7F3D0', color: '#065F46'  },
            err:  { bg: '#FEF2F2', border: '#FECACA', color: '#991B1B'  },
            info: { bg: '#fff',    border: 'rgba(59,130,246,0.18)', color: '#2D4B78' },
        }[type] ?? { bg: '#fff', border: 'rgba(59,130,246,0.18)', color: '#2D4B78' };

        const id  = 't' + Date.now();
        const div = document.createElement('div');
        div.className = 'toast';
        div.id = 'toast-' + id;
        div.style.cssText = `background:${cfg.bg};border:1px solid ${cfg.border}`;
        div.innerHTML = `
            <span style="flex:1;font-size:13px;font-weight:500;color:${cfg.color}">${msg}</span>
            <button onclick="document.getElementById('toast-${id}').remove()"
                    style="opacity:.5;cursor:pointer;background:none;border:none;padding:0;color:${cfg.color}">
                <i data-lucide="x" style="width:14px;height:14px"></i>
            </button>`;
        document.getElementById('toast-container').appendChild(div);
        lucide.createIcons();
        setTimeout(() => { document.getElementById('toast-' + id)?.remove(); }, 3200);
    }

    // ─── Sheet open / close ────────────────────────────────────────────────────
    function openSheet(id) {
        document.getElementById(id).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeSheet(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = '';
    }

    // ─── Loading state untuk tombol submit ────────────────────────────────────
    function setLoading(btnId, loading) {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        btn.disabled = loading;
        btn.dataset.origText = btn.dataset.origText ?? btn.querySelector('span')?.textContent ?? btn.textContent;
        const span = btn.querySelector('span') ?? btn;
        span.textContent = loading ? 'Menyimpan...' : btn.dataset.origText;
    }

    // ─── Auto-dismiss toast dari session flash ─────────────────────────────────
    @if(session('toast_ok'))
        showToast(@json(session('toast_ok')), 'ok');
    @endif
    @if(session('toast_err'))
        showToast(@json(session('toast_err')), 'err');
    @endif
    @if(session('toast_info'))
        showToast(@json(session('toast_info')), 'info');
    @endif
</script>

@stack('scripts')

</body>
</html>
