@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Your Review Is Live 🎉</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        Hi {{ $review->reviewer_name }}, great news — your review for
        <strong style="color:#0f172a;">{{ $review->project->title ?? 'your project' }}</strong>
        has been approved and is now published on our website. Thank you for sharing your experience!
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <div style="font-size:20px; color:#f59e0b; letter-spacing:2px; margin-bottom:8px;">
                    @for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                </div>
                <div style="font-size:14px; color:#475569; line-height:1.7;">{{ \Illuminate\Support\Str::limit($review->review ?? '', 300) }}</div>
                <div style="font-size:12px; color:#94a3b8; margin-top:12px;">— {{ $review->reviewer_name }}</div>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('home') }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    See It on Our Homepage
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0; font-size:13px; color:#94a3b8; line-height:1.7;">
        Working with you was a pleasure. If you ever need anything else, we are just an email away.
    </p>
@endsection
