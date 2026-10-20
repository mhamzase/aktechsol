@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">New Demo Booking</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        <strong style="color:#0f172a;">{{ $booking->name }}</strong> booked a demo on the website.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#475569;">
                    <tr>
                        <td style="padding:4px 0; width:90px; color:#94a3b8;">Name</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $booking->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Email</td>
                        <td style="padding:4px 0;">{{ $booking->email }}</td>
                    </tr>
                    @if($booking->phone)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Phone</td>
                        <td style="padding:4px 0;">{{ $booking->phone }}</td>
                    </tr>
                    @endif
                    @if($booking->service)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Service</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $booking->service }}</td>
                    </tr>
                    @endif
                    @if($booking->preferred_date)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Date</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $booking->preferred_date->format('M d, Y') }}</td>
                    </tr>
                    @endif
                    @if($booking->preferred_time)
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Time</td>
                        <td style="padding:4px 0;">{{ $booking->preferred_time }}</td>
                    </tr>
                    @endif
                </table>
                @if($booking->message)
                <div style="margin-top:16px; padding-top:16px; border-top:1px solid #e2e8f0; font-size:14px; color:#475569; line-height:1.7;">
                    {{ $booking->message }}
                </div>
                @endif
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('admin.demo-bookings.show', $booking) }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    View in Admin Panel
                </a>
            </td>
        </tr>
    </table>
@endsection
