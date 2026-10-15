<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal - {{ $proposal->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap');
        body { font-family: 'Sora', Arial, sans-serif; color: #0f172a; margin: 0; padding: 40px; font-size: 14px; line-height: 1.6; }
        .header { background: linear-gradient(135deg, #1e3a8a, #1d4ed8); color: #fff; padding: 32px; border-radius: 12px; margin-bottom: 32px; }
        .header h1 { margin: 0 0 8px; font-size: 26px; font-weight: 800; }
        .header p { margin: 0; opacity: 0.8; font-size: 13px; }
        .meta { display: table; width: 100%; margin-bottom: 32px; }
        .meta-row { display: table-row; }
        .meta-cell { display: table-cell; padding: 10px 16px; border-bottom: 1px solid #e2e8f0; }
        .meta-label { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 40%; }
        .meta-value { font-weight: 700; }
        .amount { font-size: 28px; font-weight: 800; color: #1d4ed8; }
        h2 { font-size: 18px; font-weight: 800; margin: 32px 0 16px; color: #0f172a; border-bottom: 2px solid #1d4ed8; padding-bottom: 8px; }
        .milestone { display: table; width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; }
        .milestone-row { display: table-row; }
        .milestone-cell { display: table-cell; padding: 12px 16px; }
        .milestone-amount { text-align: right; font-weight: 700; width: 30%; }
        .footer { margin-top: 48px; padding-top: 24px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $proposal->title }}</h1>
        <p>Prepared for {{ $proposal->client_name }} · {{ date('M d, Y') }}</p>
    </div>

    <div class="meta">
        <div class="meta-row">
            <div class="meta-cell meta-label">Total Investment</div>
            <div class="meta-cell meta-value amount">${{ number_format($proposal->amount, 2) }}</div>
        </div>
        @if($proposal->timeline)
        <div class="meta-row">
            <div class="meta-cell meta-label">Timeline</div>
            <div class="meta-cell meta-value">{{ $proposal->timeline }}</div>
        </div>
        @endif
        @if($proposal->valid_until)
        <div class="meta-row">
            <div class="meta-cell meta-label">Valid Until</div>
            <div class="meta-cell meta-value">{{ $proposal->valid_until->format('M d, Y') }}</div>
        </div>
        @endif
        <div class="meta-row">
            <div class="meta-cell meta-label">Status</div>
            <div class="meta-cell meta-value">{{ ucfirst(str_replace('_', ' ', $proposal->status)) }}</div>
        </div>
    </div>

    <h2>Project Scope</h2>
    <div>{!! $proposal->scope !!}</div>

    @if($proposal->milestones)
    <h2>Payment Milestones</h2>
    @foreach($proposal->milestones as $i => $m)
    <div class="milestone">
        <div class="milestone-row">
            <div class="milestone-cell"><strong>{{ $i + 1 }}.</strong> {{ $m['title'] }}</div>
            <div class="milestone-cell milestone-amount">${{ number_format($m['amount'], 2) }}</div>
        </div>
    </div>
    @endforeach
    @endif

    <div class="footer">
        <p>AK Tech SOL · This proposal was generated on {{ date('M d, Y') }}</p>
    </div>
</body>
</html>
