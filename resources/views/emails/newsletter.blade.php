@extends('emails.layout')

@section('content')
    {{-- Admin-composed newsletter body (rich HTML from the Quill editor). --}}
    <div style="font-size:15px; color:#334155; line-height:1.75;">
        {!! $bodyHtml !!}
    </div>

    <p style="margin:28px 0 0; padding-top:16px; border-top:1px solid #e2e8f0; font-size:12px; color:#94a3b8; line-height:1.6;">
        You received this email because you subscribed to the {{ $siteSettings->site_name ?: 'AK Tech SOL' }} newsletter.
    </p>
@endsection
