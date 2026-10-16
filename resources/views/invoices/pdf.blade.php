<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $invoice->invoice_no }}</title>
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
        /* ---------- Breakdown ---------- */
        .bd-table { width: 100%; border-collapse: collapse; }
        .bd-table td { padding: 8px 0; font-size: 12px; }
        .bd-table .lbl { color: #64748b; }
        .bd-table .val { text-align: right; font-weight: bold; color: #0f172a; }
        .bd-table .total td {
            border-top: 2px solid #0f172a;
            padding-top: 12px;
            font-size: 14px;
        }
        .bd-table .total .val { color: #1d4ed8; font-size: 16px; }
        /* ---------- Meta ---------- */
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 6px 0; font-size: 12px; }
        .meta-table .k { color: #94a3b8; width: 40%; }
        .meta-table .v { color: #0f172a; font-weight: bold; }
        .notes { color: #475569; font-size: 12px; white-space: pre-line; }
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
        <div class="kicker">INVOICE {{ $invoice->invoice_no }}</div>
        <h1>{{ $invoice->title }}</h1>
        <div class="prepared">Billed to {{ $invoice->clientName() }}</div>
        <table class="stats-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="stat-box">
                    <div class="lbl">Total</div>
                    <div class="val">${{ number_format($invoice->amount, 2) }}</div>
                </td>
                <td class="stat-gap"></td>
                <td class="stat-box">
                    <div class="lbl">Paid</div>
                    <div class="val">${{ number_format($invoice->paid_amount, 2) }}</div>
                </td>
                <td class="stat-gap"></td>
                <td class="stat-box">
                    <div class="lbl">Balance Due</div>
                    <div class="val">${{ number_format($invoice->balance(), 2) }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ============ DETAILS ============ --}}
    <div class="card">
        <h2>Invoice Details</h2>
        <table class="meta-table" cellpadding="0" cellspacing="0">
            <tr><td class="k">Invoice No.</td><td class="v">{{ $invoice->invoice_no }}</td></tr>
            <tr><td class="k">Status</td><td class="v">{{ $invoice->statusLabel() }}</td></tr>
            @if($invoice->due_date)
            <tr><td class="k">Due Date</td><td class="v">{{ $invoice->due_date->format('M d, Y') }}</td></tr>
            @endif
            <tr><td class="k">Issued</td><td class="v">{{ $invoice->created_at->format('M d, Y') }}</td></tr>
            @if($invoice->project)
            <tr><td class="k">Project</td><td class="v">{{ $invoice->project->title }}</td></tr>
            @endif
            <tr><td class="k">Client</td><td class="v">{{ $invoice->clientName() }}</td></tr>
            @if($invoice->clientEmail())
            <tr><td class="k">Email</td><td class="v">{{ $invoice->clientEmail() }}</td></tr>
            @endif
        </table>
    </div>

    {{-- ============ BREAKDOWN ============ --}}
    <div class="card">
        <h2>Amount Breakdown</h2>
        <table class="bd-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="lbl">Invoice total</td>
                <td class="val">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="lbl">Paid to date</td>
                <td class="val">${{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr class="total">
                <td class="lbl"><strong>Balance due</strong></td>
                <td class="val">${{ number_format($invoice->balance(), 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- ============ NOTES ============ --}}
    @if($invoice->notes)
    <div class="card">
        <h2>Payment Instructions</h2>
        <div class="notes">{{ $invoice->notes }}</div>
    </div>
    @endif

    {{-- ============ FOOTER ============ --}}
    <div class="foot">
        <strong>AK Tech SOL</strong> &nbsp;·&nbsp; Generated on {{ date('M d, Y') }}
    </div>

</body>
</html>
