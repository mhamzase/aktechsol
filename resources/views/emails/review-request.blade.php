@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Hi {{ $project->client_name }},</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Your project <strong style="color:#0f172a;">{{ $project->title }}</strong> has been delivered.
        How was your experience working with us? Your feedback helps us improve — and helps other clients choose us with confidence.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center" style="font-size:28px; letter-spacing:4px; color:#f59e0b;">
                ★★★★★
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ $reviewUrl }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    Leave a Review
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:13px; color:#94a3b8; line-height:1.6;">
        It takes less than a minute. Thank you for choosing AK Tech SOL.
    </p>
@endsection
