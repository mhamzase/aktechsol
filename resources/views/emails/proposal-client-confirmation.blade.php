@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">{{ $copy['heading'] ?? 'Proposal Update' }}</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Hi {{ $proposal->client_name }}, {{ $copy['intro'] ?? '' }}
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Proposal</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#0f172a; padding:6px 0;">{{ $proposal->title }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Amount</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#0f172a; padding:6px 0;">{{ \App\Support\Money::format($proposal->amount, $proposal->currency) }}</td>
                    </tr>
                    @if($proposal->timeline)
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Timeline</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#0f172a; padding:6px 0;">{{ $proposal->timeline }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size:14px; color:#64748b; padding:6px 0;">Your response</td>
                        <td align="right" style="font-size:14px; font-weight:700; color:#1d4ed8; padding:6px 0;">{{ ucfirst(str_replace('_', ' ', $proposal->status)) }}</td>
                    </tr>
                </table>
                @if($proposal->client_note)
                <div style="margin-top:16px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <div style="font-size:12px; color:#94a3b8; margin-bottom:6px;">YOUR NOTE</div>
                    <div style="font-size:14px; color:#475569; line-height:1.7;">{{ $proposal->client_note }}</div>
                </div>
                @endif
            </td>
        </tr>
    </table>

    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        {{ $copy['next'] ?? '' }}
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ $proposal->publicUrl() }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View Your Proposal
                </a>
            </td>
        </tr>
    </table>
@endsection
