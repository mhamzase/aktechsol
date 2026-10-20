@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">New Job Application</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        <strong style="color:#0f172a;">{{ $application->name }}</strong> applied for
        <strong style="color:#0f172a;">{{ $application->job?->title ?? 'a position' }}</strong> on the careers page.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#475569;">
                    <tr>
                        <td style="padding:4px 0; width:90px; color:#94a3b8;">Name</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $application->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Email</td>
                        <td style="padding:4px 0;">{{ $application->email }}</td>
                    </tr>
                    @if($application->phone)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Phone</td>
                        <td style="padding:4px 0;">{{ $application->phone }}</td>
                    </tr>
                    @endif
                    @if($application->job)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Position</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $application->job->title }} ({{ ucfirst($application->job->type) }} · {{ ucfirst($application->job->location_type) }})</td>
                    </tr>
                    @endif
                    @if($application->portfolio_url)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Portfolio</td>
                        <td style="padding:4px 0;"><a href="{{ $application->portfolio_url }}" style="color:#1d4ed8;">{{ $application->portfolio_url }}</a></td>
                    </tr>
                    @endif
                </table>
                @if($application->cover_note)
                <div style="margin-top:16px; padding-top:16px; border-top:1px solid #e2e8f0; font-size:14px; color:#475569; line-height:1.7;">
                    {{ $application->cover_note }}
                </div>
                @endif
                @if($application->getFirstMediaUrl('resume'))
                <p style="margin:16px 0 0; font-size:13px; color:#94a3b8;">The candidate also uploaded a CV. Open the application in the admin panel to download it.</p>
                @endif
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('admin.job-applications.show', $application) }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View in Admin Panel
                </a>
            </td>
        </tr>
    </table>
@endsection
