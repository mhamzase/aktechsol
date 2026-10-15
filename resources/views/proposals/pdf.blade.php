<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal - {{ $proposal->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; }
        body { font-family: 'Sora', Arial, sans-serif; color: #0f172a; margin: 0; padding: 0; font-size: 13px; line-height: 1.6; background: #fff; }

        .hero { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2f6fed 100%); color: #fff; padding: 36px 40px; }
        .hero .label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.7; margin-bottom: 8px; }
        .hero h1 { margin: 0 0 6px; font-size: 26px; font-weight: 800; }
        .hero .client { font-size: 13px; opacity: 0.8; }
        .hero-stats { margin-top: 20px; }
        .stat { display: inline-block; background: rgba(255,255,255,0.12); border-radius: 10px; padding: 10px 18px; margin-right: 10px; }
        .stat .s-label { font-size: 10px; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat .s-value { font-size: 16px; font-weight: 800; margin-top: 2px; }

        .body { padding: 32px 40px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 24px; margin-bottom: 20px; }
        .card h2 { margin: 0 0 14px; font-size: 16px; font-weight: 800; color: #0f172a; }
        .card .scope { color: #475569; font-size: 13px; }
        .card .scope ul { padding-left: 20px; }
        .card .scope li { margin-bottom: 4px; }

        .milestone { background: #f8fafc; border-radius: 10px; padding: 12px 18px; margin-bottom: 8px; }
        .milestone .m-num { display: inline-block; width: 28px; height: 28px; background: rgba(29,78,216,0.1); color: #1d4ed8; font-weight: 700; font-size: 12px; text-align: center; line-height: 28px; border-radius: 8px; margin-right: 10px; }
        .milestone .m-title { font-weight: 600; color: #1e293b; }
        .milestone .m-amount { float: right; font-weight: 800; color: #0f172a; }

        .footer { padding: 24px 40px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8; }
        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-sent { background: #dbeafe; color: #1d4ed8; }
        .status-accepted { background: #dcfce7; color: #15803d; }
        .status-draft { background: #f1f5f9; color: #64748b; }
    </style>
</head>
<body>
    <div class="hero">
        <div class="label">Proposal</div>
        <h1>{{ $proposal->title }}</h1>
        <div class="client">Prepared for {{ $proposal->client_name }} · {{ $proposal->client_email }}</div>
        <div class="hero-stats">
            <div class="stat">
                <div class="s-label">Total Investment</div>
                <div class="s-value">${{ number_format($proposal->amount, 2) }}</div>
            </div>
            @if($proposal->timeline)
            <div class="stat">
                <div class="s-label">Timeline</div>
                <div class="s-value">{{ $proposal->timeline }}</div>
            </div>
            @endif
            @if($proposal->valid_until)
            <div class="stat">
                <div class="s-label">Valid Until</div>
                <div class="s-value">{{ $proposal->valid_until->format('M d, Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="body">
        <div class="card">
            <h2>Project Scope</h2>
            <div class="scope">{!! $proposal->scope !!}</div>
        </div>

        @if($proposal->milestones && count($proposal->milestones))
        <div class="card">
            <h2>Payment Milestones</h2>
            @foreach($proposal->milestones as $i => $m)
            <div class="milestone">
                <span class="m-num">{{ $i + 1 }}</span>
                <span class="m-title">{{ $m['title'] }}</span>
                <span class="m-amount">${{ number_format($m['amount'], 2) }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="card">
            <h2>Proposal Status</h2>
            <span class="status-badge status-{{ $proposal->status }}">{{ str_replace('_', ' ', $proposal->status) }}</span>
            <span style="margin-left: 12px; font-size: 12px; color: #64748b;">Generated on {{ date('M d, Y') }}</span>
        </div>
    </div>

    <div class="footer">
        <p style="margin:0;"><strong>AK Tech SOL</strong> · Digital Services Agency</p>
        <p style="margin:4px 0 0;">This is a system-generated proposal document.</p>
    </div>
</body>
</html>
