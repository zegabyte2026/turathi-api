<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.qr_export_title') }} — {{ $site->name['fr'] ?? $site->name }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Reem+Kufi:wght@400;700&family=IBM+Plex+Sans:wght@400;600;700&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'IBM Plex Sans', sans-serif; background: #F3E9CF; color: #0D211D; padding: 24px; }

        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
        .toolbar h1 { font-size: 22px; font-weight: 700; }
        .toolbar .subtitle { font-size: 13px; color: #666; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
        .btn-back { background: #3E8E7E; color: #F3E9CF; }
        .btn-print { background: #B85C38; color: #F3E9CF; }

        .site-header { background: #0D211D; color: #F3E9CF; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .site-header h2 { font-size: 20px; font-weight: 700; }
        .site-header .count { background: #3E8E7E; color: #F3E9CF; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }

        .qr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
        .qr-card { background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); page-break-inside: avoid; break-inside: avoid; }
        .qr-card canvas { margin: 10px auto; display: block; }
        .qr-card h3 { font-size: 14px; font-weight: 700; margin-bottom: 6px; color: #0D211D; line-height: 1.3; }
        .qr-card .badge { display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px; }
        .badge-site { background: #3E8E7E; color: white; }
        .badge-endroit { background: #B85C38; color: white; }
        .qr-card .site-name { font-size: 11px; color: #888; margin-top: 4px; }
        .qr-card .qr-id { font-size: 9px; color: #bbb; font-family: monospace; margin-top: 6px; word-break: break-all; }

        .empty { text-align: center; padding: 80px 20px; color: #999; }
        .empty h2 { font-size: 18px; margin-bottom: 8px; }
        .empty p { font-size: 14px; }

        @media print {
            body { background: white; padding: 16px; }
            .toolbar, .site-header { display: none !important; }
            .qr-card { box-shadow: 0 0 0 1px #ddd; }
            .qr-grid { gap: 12px; }
        }
    </style>
</head>
<body>

<div class="toolbar no-print">
    <div>
        <a href="{{ route('admin.qr.index') }}" class="btn btn-back">{{ __('admin.qr_export_back') }}</a>
    </div>
    <div style="text-align: center;">
        <h1>{{ __('admin.qr_export_title') }}</h1>
        <p class="subtitle">{{ $site->name['fr'] ?? $site->name }}</p>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-print">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            {{ __('admin.qr_export_print') }}
        </button>
    </div>
</div>

<div class="site-header no-print">
    <h2>{{ $site->name['fr'] ?? $site->name }}</h2>
    <span class="count">{{ $qrCodes->count() }} {{ __('admin.qr_export_count_label') }}</span>
</div>

<div class="qr-grid" id="qrGrid">
    @forelse($qrCodes as $qr)
        <div class="qr-card">
            <span class="badge {{ $qr->type === 'site' ? 'badge-site' : 'badge-endroit' }}">{{ $qr->type === 'site' ? __('admin.qr_site_badge') : __('admin.qr_endroit_badge') }}</span>
            <h3>{{ $qr->type === 'site' ? ($qr->site->name['fr'] ?? $site->name) : ($qr->endroit->title['fr'] ?? '—') }}</h3>
            @if($qr->type === 'endroit' && $qr->endroit)
                <p class="site-name">{{ $qr->site->name['fr'] ?? '' }}</p>
            @endif
            <canvas id="qr-{{ $qr->id }}" width="180" height="180"></canvas>
            <p class="qr-id">{{ $qr->qr_code_id }}</p>
        </div>
    @empty
        <div class="empty" style="grid-column: 1 / -1;">
            <h2>{{ __('admin.qr_export_empty_title') }}</h2>
            <p>{{ __('admin.qr_export_empty_text') }}</p>
        </div>
    @endforelse
</div>

<script>
    var baseUrl = '{{ config("app.url", "http://127.0.0.1:8000") }}';
    @foreach($qrCodes as $qr)
        (function() {
            var canvas = document.getElementById('qr-{{ $qr->id }}');
            if (!canvas) return;
            new QRious({
                element: canvas,
                value: baseUrl + '/api/v1/scan/{{ $qr->qr_code_id }}',
                size: 180,
                level: 'M',
                foreground: '#0D211D',
                background: '#FFFFFF'
            });
        })();
    @endforeach
</script>

</body>
</html>
