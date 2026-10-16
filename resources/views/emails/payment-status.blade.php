@extends('emails.layout')

@section('content')
    @php
        $color = $verified ? '#047857' : '#b91c1c';
        $bg = $verified ? '#d1fae5' : '#fee2e2';
        $title = $verified ? 'Payment Verified ✓' : 'Payment Not Verified';
    @endphp
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">{{ $title }}</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Hi {{ $invoice->clientName() }},
    </p>
    @if($verified)
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Your payment of <strong style="color:#0f172a;">${{ number_format($payment->amount, 2) }}</strong>
        for invoice <strong style="color:#0f172a;">{{ $invoice->invoice_no }}</strong> has been verified. Thank you!
    </p>
    @else
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        We could not verify your payment of <strong style="color:#0f172a;">${{ number_format($payment->amount, 2) }}</strong>
        for invoice <strong style="color:#0f172a;">{{ $invoice->invoice_no }}</strong>.
        Please check your payment details and submit a new proof.
    </p>
    @endif

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td style="padding:16px 20px; background:{{ $bg }}; border-radius:12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:14px; color:#475569;">Invoice Balance</td>
                        <td style="font-size:16px; font-weight:800; color:{{ $color }}; text-align:right;">${{ number_format($invoice->balance(), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('invoices.show', $invoice->token) }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View Invoice
                </a>
            </td>
        </tr>
    </table>
@endsection
