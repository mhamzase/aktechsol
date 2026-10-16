@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Payment Proof Submitted</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        <strong style="color:#0f172a;">{{ $invoice->clientName() }}</strong> submitted a payment proof for
        <strong style="color:#0f172a;">{{ $invoice->invoice_no }}</strong> ({{ $invoice->title }}).
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:4px 0;">Amount</td>
                        <td style="font-size:14px; font-weight:700; color:#0f172a; text-align:right; padding:4px 0;">${{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:4px 0;">Method</td>
                        <td style="font-size:14px; font-weight:700; color:#0f172a; text-align:right; padding:4px 0;">{{ $payment->method }}</td>
                    </tr>
                    @if($payment->reference)
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:4px 0;">Reference</td>
                        <td style="font-size:14px; font-weight:700; color:#0f172a; text-align:right; padding:4px 0;">{{ $payment->reference }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:4px 0;">Invoice Balance</td>
                        <td style="font-size:14px; font-weight:700; color:#0f172a; text-align:right; padding:4px 0;">${{ number_format($invoice->balance(), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('admin.invoices.show', $invoice) }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    Review Payment
                </a>
            </td>
        </tr>
    </table>
@endsection
