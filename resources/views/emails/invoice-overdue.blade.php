@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Payment Overdue</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Hi {{ $invoice->clientName() }}, this is a friendly reminder that the payment for invoice
        <strong style="color:#0f172a;">{{ $invoice->invoice_no }}</strong> is now past its due date.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Invoice</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#0f172a; padding:6px 0;">{{ $invoice->invoice_no }} — {{ $invoice->title }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Due date</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#dc2626; padding:6px 0;">{{ $invoice->due_date?->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Total</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#0f172a; padding:6px 0;">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Paid</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#047857; padding:6px 0;">${{ number_format($invoice->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:16px; font-weight:800; color:#0f172a; padding:6px 0; border-top:1px solid #e2e8f0;">Balance due</td>
                        <td align="right" style="font-size:16px; font-weight:800; color:#dc2626; padding:6px 0; border-top:1px solid #e2e8f0;">${{ number_format($invoice->balance(), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        You can view the invoice and submit your payment from the secure link below.
        If you have already paid, please ignore this reminder.
    </p>

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
@endsection
