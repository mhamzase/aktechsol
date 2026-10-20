@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Hi {{ $invoice->clientName() }},</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Please find your invoice <strong style="color:#0f172a;">{{ $invoice->invoice_no }}</strong> below:
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <div style="font-size:16px; font-weight:700; color:#0f172a; margin-bottom:4px;">{{ $invoice->title }}</div>
                <div style="font-size:13px; color:#64748b; margin-bottom:16px;">Invoice {{ $invoice->invoice_no }}</div>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Amount</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#0f172a; padding:6px 0;">{{ \App\Support\Money::format($invoice->amount, $invoice->currency) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Paid</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#047857; padding:6px 0;">{{ \App\Support\Money::format($invoice->paid_amount, $invoice->currency) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0; border-top:1px solid #e2e8f0;">Balance due</td>
                        <td align="right" style="font-size:16px; font-weight:800; color:#1d4ed8; padding:6px 0; border-top:1px solid #e2e8f0;">{{ \App\Support\Money::format($invoice->balance(), $invoice->currency) }}</td>
                    </tr>
                    @if($invoice->due_date)
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Due date</td>
                        <td align="right" style="font-size:14px; font-weight:600; color:#0f172a; padding:6px 0;">{{ $invoice->due_date->format('M d, Y') }}</td>
                    </tr>
                    @endif
                </table>
                @if($invoice->notes)
                <div style="margin-top:16px; font-size:13px; color:#475569; line-height:1.6; border-top:1px solid #e2e8f0; padding-top:16px;">
                    {{ $invoice->notes }}
                </div>
                @endif
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ $invoice->invoiceUrl() }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View &amp; Pay Invoice
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:13px; color:#94a3b8; line-height:1.6;">
        After paying, please upload your payment screenshot on the invoice page so we can verify it.
    </p>
@endsection
