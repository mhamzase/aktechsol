<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Proposal - {{ $proposal->title }}</title>
    <style>
        @font-face {
            font-family: 'Sora';
            src: url('{{ public_path('assets/fonts/sora-pdf/Sora-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Sora';
            src: url('{{ public_path('assets/fonts/sora-pdf/Sora-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: 'Sora', DejaVu Sans, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 36px;
            font-size: 12px;
            line-height: 1.7;
        }
        /* ---------- Hero ---------- */
        .hero {
            background-color: #1d4ed8;
            color: #ffffff;
            border-radius: 14px;
            padding: 30px 32px;
            margin-bottom: 22px;
        }
        .hero .kicker {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #bfdbfe;
            margin-bottom: 10px;
        }
        .hero h1 {
            font-size: 23px;
            font-weight: bold;
            color: #ffffff;
            margin: 0 0 6px 0;
            line-height: 1.3;
        }
        .hero .prepared {
            font-size: 12px;
            color: #bfdbfe;
            margin-bottom: 18px;
        }
        .stats-table { width: 100%; border-collapse: collapse; }
        .stat-box {
            background-color: #1e40af;
            border-radius: 8px;
            padding: 10px 16px;
            width: 32%;
        }
        .stat-box .lbl { font-size: 9px; color: #bfdbfe; text-transform: uppercase; letter-spacing: 1px; }
        .stat-box .val { font-size: 14px; font-weight: bold; color: #ffffff; margin-top: 3px; }
        .stat-gap { width: 2%; }
        /* ---------- Cards ---------- */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px 28px;
            margin-bottom: 20px;
        }
        .card h2 {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 12px 0;
        }
        .scope { color: #475569; font-size: 12px; }
        .scope ul { padding-left: 18px; margin: 8px 0 0 0; }
        .scope li { margin-bottom: 5px; }
        .scope p { margin: 0 0 8px 0; }
        /* ---------- Milestones ---------- */
        .ms-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .ms-row td {
            background-color: #f8fafc;
            padding: 12px 16px;
            vertical-align: middle;
        }
        .ms-row td:first-child { border-radius: 8px 0 0 8px; width: 40px; padding-right: 0; }
        .ms-row td:last-child { border-radius: 0 8px 8px 0; text-align: right; }
        .ms-num {
            background-color: #dbeafe; color: #1d4ed8;
            font-weight: bold; font-size: 12px; text-align: center;
            border-radius: 6px;
            padding: 6px 10px;
        }
        .ms-title { font-weight: bold; font-size: 12px; color: #1e293b; }
        .ms-amount { font-weight: bold; font-size: 13px; color: #0f172a; }
        /* ---------- Footer ---------- */
        .foot {
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    {{-- ============ HERO ============ --}}
    <div class="hero">
        <div class="kicker">PROPOSAL</div>
        <h1>{{ $proposal->title }}</h1>
        <div class="prepared">Prepared for {{ $proposal->client_name }}</div>
        <table class="stats-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="stat-box">
                    <div class="lbl">Total Investment</div>
                    <div class="val">${{ number_format($proposal->amount, 2) }}</div>
                </td>
                <td class="stat-gap"></td>
                @if($proposal->timeline)
                <td class="stat-box">
                    <div class="lbl">Timeline</div>
                    <div class="val">{{ $proposal->timeline }}</div>
                </td>
                <td class="stat-gap"></td>
                @endif
                @if($proposal->valid_until)
                <td class="stat-box">
                    <div class="lbl">Valid Until</div>
                    <div class="val">{{ $proposal->valid_until->format('M d, Y') }}</div>
                </td>
                @endif
            </tr>
        </table>
    </div>

    {{-- ============ SCOPE ============ --}}
    <div class="card">
        <h2>Project Scope</h2>
        <div class="scope">{!! $proposal->scope !!}</div>
    </div>

    {{-- ============ MILESTONES ============ --}}
    @if($proposal->milestones && count($proposal->milestones))
    <div class="card">
        <h2>Payment Milestones</h2>
        <table class="ms-table" cellpadding="0" cellspacing="0">
            @foreach($proposal->milestones as $i => $m)
            <tr class="ms-row">
                <td><span class="ms-num">{{ $i + 1 }}</span></td>
                <td><span class="ms-title">{{ $m['title'] }}</span></td>
                <td><span class="ms-amount">${{ number_format($m['amount'], 2) }}</span></td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    {{-- ============ FOOTER ============ --}}
    <div class="foot">
        <strong>AK Tech SOL</strong> &nbsp;·&nbsp; Generated on {{ date('M d, Y') }}
    </div>

</body>
</html>
