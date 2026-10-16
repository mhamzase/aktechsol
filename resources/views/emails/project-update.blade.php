@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Hi {{ $project->client_name }},</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        There's a new update on your project <strong style="color:#0f172a;">{{ $project->title }}</strong>:
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <div style="font-size:16px; font-weight:700; color:#0f172a; margin-bottom:8px;">{{ $update->title }}</div>
                @if($update->note)
                <div style="font-size:14px; color:#475569; line-height:1.6;">{{ $update->note }}</div>
                @endif
                <div style="margin-top:12px;">
                    <span style="display:inline-block; padding:4px 12px; background:#dbeafe; color:#1d4ed8; font-size:12px; font-weight:700; border-radius:20px;">
                        {{ $project->statusLabel() }} · {{ $project->progress }}% complete
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ $project->trackingUrl() }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    Track Your Project
                </a>
            </td>
        </tr>
    </table>
@endsection
