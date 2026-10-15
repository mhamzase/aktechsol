<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal - {{ $proposal->title }}</title>
    <style>
        @font-face {
            font-family: 'Sora';
            src: url('{{ public_path('assets/fonts/sora-pdf/Sora-Regular.ttf') }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Sora';
            src: url('{{ public_path('assets/fonts/sora-pdf/Sora-Bold.ttf') }}') format('truetype');
            font-weight: bold;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Sora', Arial, sans-serif; color: #0f172a; margin: 0; padding: 32px; font-size: 13px; line-height: 1.6; background: #fff; }

        /* Hero card — solid colors for dompdf */
        .hero { background: #1d4ed8; border-radius: 12px; padding: 32px; color: #ffffff; margin-bottom: 20px; }
        .hero .label { font-size: 10px; font-weight: 700; letter-spacing: 2px; color: #bfdbfe; margin-bottom: 8px; }
        .hero h1 { margin: 0 0 6px; font-size: 24px; font-weight: 800; color: #ffffff; }
        .hero .prepared { font-size: 13px; color: #bfdbfe; }
        .hero-stats { margin-top: 18px; }
        .hstat { display: inline-block; background: #1e40af; border-radius: 8px; padding: 10px 16px; margin-right: 10px; margin-bottom: 8px; }
        .hstat .l { font-size: 10px; color: #bfdbfe; }
        .hstat .v { font-size: 15px; font-weight: 800; margin-top: 2px; color: #ffffff; }

        /* Content cards */
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
        .card h2 { margin: 0 0 14px; font-size: 16px; font-weight: 800; color: #0f172a; }
        .card .text { color: #475569; font-size: 13px; }
        .card .text ul { padding-left: 18px; margin: 10px 0 0; }
        .card .text li { margin-bottom: 5px; color: #475569; }

        /* Milestones */
        .ms { background: #f8fafc; border-radius: 10px; padding: 12px 18px; margin-bottom: 8px; }
        .ms .num { display: inline-block; width: 28px; height: 28px; background: #dbeafe; color: #1d4ed8; font-weight: 700; font-size: 12px; text-align: center; line-height: 28px; border-radius: 8px; margin-right: 10px; }
        .ms .t { font-weight: 700; font-size: 13px; color: #1e293b; }
        .ms .a { float: right; font-weight: 800; font-size: 14px; color: #0f172a; }

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
