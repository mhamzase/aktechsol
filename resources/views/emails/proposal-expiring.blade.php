@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Your Proposal Expires Soon</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Hi {{ $proposal->client_name }}, just a quick reminder — your proposal
        <strong style="color:#0f172a;">{{ $proposal->title }}</strong> is valid until
        <strong style="color:#dc2626;">{{ $proposal->valid_until?->format('M d, Y') }}</strong> (3 days from now).
    </p>

    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        After this date the proposal — including its pricing and timeline — will no longer be available.
        You can review it and accept it from the secure link below before it expires.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ $proposal->publicUrl() }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View Proposal
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:13px; color:#94a3b8; line-height:1.7;">
        Questions about the proposal? Just reply to this email and our team will help you out.
    </p>
@endsection
