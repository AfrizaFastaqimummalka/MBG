<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'My Bike Garage') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-gradient: linear-gradient(160deg, #EBF4FF 0%, #F5F9FF 50%, #EEF4FF 100%);
            --surface: #FFFFFF;
            --surface-2: #EFF6FF;
            --border: rgba(59,130,246,0.14);
            --ink: #0D1F3C; --ink-2: #2D4B78; --ink-3: #6B8DB8;
            --primary: #2563EB;
        }
        *, *::before, *::after { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        html, body { font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; background: var(--bg-gradient); color: var(--ink); -webkit-font-smoothing: antialiased; min-height: 100dvh; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .afu  { animation: fadeUp .32s cubic-bezier(.16,1,.3,1) both; }
        .d1   { animation-delay: .06s; }
        .d2   { animation-delay: .12s; }
        .tap  { cursor:pointer; transition: opacity .12s, transform .12s; }
        .tap:active { opacity:.72; transform:scale(.97); }

        .inp {
            width:100%; padding:11px 14px;
            background:var(--surface); border:1.5px solid rgba(59,130,246,0.18);
            border-radius:12px; color:var(--ink); font-size:15px;
            font-family:'Plus Jakarta Sans',sans-serif; font-weight:500;
            outline:none; transition:border-color .15s, box-shadow .15s;
            -webkit-appearance:none; appearance:none;
        }
        .inp:focus { border-color:var(--primary); box-shadow:0 0 0 3.5px rgba(37,99,235,0.12); }
        .inp::placeholder { color:var(--ink-3); font-weight:400; }
        .inp.pl { padding-left:42px; }

        .btn-primary {
            width:100%; padding:13px 20px;
            background:linear-gradient(135deg,#3B82F6 0%,#1D4ED8 100%);
            color:#fff; border:none; border-radius:13px;
            font-family:'Plus Jakarta Sans',sans-serif; font-size:15px; font-weight:700;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
            transition:box-shadow .2s,transform .12s;
            box-shadow:0 2px 16px rgba(37,99,235,0.35);
        }
        .btn-primary:active { transform:scale(.98); }
        .btn-primary:disabled { opacity:.42; cursor:not-allowed; box-shadow:none; }

        .field { display:flex; flex-direction:column; gap:6px; }
        .field label { font-size:12px; font-weight:600; color:var(--ink-2); }
        * { scrollbar-width:none; }
        *::-webkit-scrollbar { display:none; }
    </style>
</head>
<body style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100dvh;padding:24px 24px 40px;position:relative;overflow:hidden">

    {{-- Decorative blobs --}}
    <div style="position:fixed;top:-80px;right:-60px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,0.12) 0%,transparent 70%);pointer-events:none"></div>
    <div style="position:fixed;bottom:-60px;left:-40px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,0.10) 0%,transparent 70%);pointer-events:none"></div>
    <div style="position:fixed;inset:0;opacity:.25;pointer-events:none;background-image:radial-gradient(rgba(37,99,235,0.4) 1px,transparent 1px);background-size:28px 28px"></div>

    <div style="width:100%;max-width:360px;position:relative;z-index:1">
        @yield('content')
    </div>

    <script>
        lucide.createIcons();

        function setLoading(btnId, loading) {
            const btn = document.getElementById(btnId);
            if (!btn) return;
            btn.disabled = loading;
            const span = btn.querySelector('span') ?? btn;
            if (loading) {
                btn.dataset.orig = span.textContent;
                span.textContent = 'Memproses...';
            } else {
                span.textContent = btn.dataset.orig ?? span.textContent;
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
