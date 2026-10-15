<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal - {{ $proposal->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; }
        body { font-family: 'Sora', Arial, sans-serif; color: #0f172a; margin: 0; padding: 32px; font-size: 13px; line-height: 1.6; background: #fff; }

        /* Hero card — matches client page */
        .hero { background: linear-gradient(135deg, #1d4ed8 0%, #12307e 100%); border-radius: 20px; padding: 36px; color: #fff; margin-bottom: 20px; }
        .hero .label { font-size: 10px; font-weight: 700; letter-spacing: 2px; color: rgba(191,219,254,0.7); margin-bottom: 8px; }
        .hero h1 { margin: 0 0 6px; font-size: 24px; font-weight: 800; }
        .hero .prepared { font-size: 13px; color: rgba(191,219,254,0.8); }
        .hero-stats { margin-top: 18px; }
        .hstat { display: inline-block; background: rgba(255,255,255,0.1); border-radius: 10px; padding: 10px 16px; margin-right: 10px; margin-bottom: 8px; }
        .hstat .l { font-size: 10px; color: rgba(191,219,254,0.7); }
        .hstat .v { font-size: 15px; font-weight: 800; margin-top: 2px; }

        /* Content cards — matches client page */
        .card { background: #fff; border: 1px solid #f1f5f9; border-radius: 20px; padding: 28px; margin-bottom: 20px; }
        .card h2 { margin: 0 0 14px; font-size: 16px; font-weight: 800; }
        .card .text { color: #475569; font-size: 13px; }
        .card .text ul { padding-left: 18px; margin: 10px 0 0; }
        .card .text li { margin-bottom: 5px; color: #475569; }

        /* Milestones — matches client page */
        .ms { background: #f8fafc; border-radius: 14px; padding: 14px 18px; margin-bottom: 10px; }
        .ms .num { display: inline-block; width: 28px; height: 28px; background: rgba(29,78,216,0.1); color: #1d4ed8; font-weight: 700; font-size: 12px; text-align: center; line-height: 28px; border-radius: 8px; margin-right: 10px; }
        .ms .t { font-weight: 700; font-size: 13px; color: #1e293b; }
        .ms .a { float: right; font-weight: 800; font-size: 14px; }

        .foot { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    {{-- Hero --}}
    <div class="hero">
        <div class="label">PROPOSAL</div>
        <h1>{{ $proposal->title }}</h1>
        <div class="prepared">Prepared for {{ $proposal->client_name }}</div>
        <div class="hero-stats">
            <div class="hstat">
                <div class="l">Total Investment</div>
                <div class="v">${{ number_format($proposal->amount, 2) }}</div>
            </div>
            @if($proposal->timeline)
            <div class="hstat">
                <div class="l">Timeline</div>
                <div class="v">{{ $proposal->timeline }}</div>
            </div>
            @endif
            @if($proposal->valid_until)
            <div class="hstat">
                <div class="l">Valid Until</div>
                <div class="v">{{ $proposal->valid_until->format('M d, Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Scope --}}
    <div class="card">
        <h2>Project Scope</h2>
        <div class="text">{!! $proposal->scope !!}</div>
    </div>

    {{-- Milestones --}}
    @if($proposal->milestones && count($proposal->milestones))
    <div class="card">
        <h2>Payment Milestones</h2>
        @foreach($proposal->milestones as $i => $m)
        <div class="ms">
            <span class="num">{{ $i + 1 }}</span>
            <span class="t">{{ $m['title'] }}</span>
            <span class="a">${{ number_format($m['amount'], 2) }}</span>
        </div>
        @endforeach
    </div>
    @endif

    <div class="foot">
        <strong>AK Tech SOL</strong> · Generated on {{ date('M d, Y') }}
    </div>
</body>
</html>
