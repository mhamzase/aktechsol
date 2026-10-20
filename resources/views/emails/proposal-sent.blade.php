@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Hi {{ $proposal->client_name }},</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Thank you for your interest! We've prepared a detailed proposal for your project.
        Please review it at your convenience.
    </p>

    {{-- Proposal summary card --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <div style="font-size:16px; font-weight:700; color:#0f172a; margin-bottom:12px;">{{ $proposal->title }}</div>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:6px 0; font-size:14px; color:#64748b;">Total Investment</td>
                        <td align="right" style="padding:6px 0; font-size:16px; font-weight:800; color:#1d4ed8;">{{ \App\Support\Money::format($proposal->amount, $proposal->currency) }}</td>
                    </tr>
                    @if($proposal->timeline)
                    <tr>
                        <td style="padding:6px 0; font-size:14px; color:#64748b;">Timeline</td>
                        <td align="right" style="padding:6px 0; font-size:14px; font-weight:600; color:#0f172a;">{{ $proposal->timeline }}</td>
                    </tr>
                    @endif
                    @if($proposal->valid_until)
                    <tr>
                        <td style="padding:6px 0; font-size:14px; color:#64748b;">Valid Until</td>
                        <td align="right" style="padding:6px 0; font-size:14px; font-weight:600; color:#dc2626;">{{ $proposal->valid_until->format('M d, Y') }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    {{-- CTA --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ $proposal->publicUrl() }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View & Respond to Proposal
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:13px; color:#94a3b8; line-height:1.6;">
        You can accept the proposal, request changes, or decline, directly from the link above.
        No account needed.
    </p>
@endsection
