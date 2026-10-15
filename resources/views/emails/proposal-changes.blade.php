@extends('emails.layout')

@section('content')
    <div style="text-align:center; margin-bottom:24px;">
        <div style="display:inline-block; width:64px; height:64px; background:#fef3c7; border-radius:50%; line-height:64px; font-size:28px;">📝</div>
    </div>
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a; text-align:center;">Changes Requested</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7; text-align:center;">
        <strong style="color:#0f172a;">{{ $proposal->client_name }}</strong> has requested changes to the proposal:
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#fffbeb; border:1px solid #fde68a; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <div style="font-size:16px; font-weight:700; color:#0f172a; margin-bottom:8px;">{{ $proposal->title }}</div>
                <div style="font-size:14px; color:#475569;">Amount: <strong>${{ number_format($proposal->amount, 2) }}</strong></div>
                @if($proposal->client_note)
                <div style="margin-top:12px; padding:12px; background:#ffffff; border-radius:8px; font-size:14px; color:#475569;">
                    <strong>Client feedback:</strong><br>{{ $proposal->client_note }}
                </div>
                @else
                <div style="margin-top:12px; font-size:14px; color:#94a3b8; font-style:italic;">No note provided.</div>
                @endif
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('admin.proposals.show', $proposal) }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    Review & Update Proposal
                </a>
            </td>
        </tr>
    </table>
@endsection
