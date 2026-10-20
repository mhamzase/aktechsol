@extends('emails.layout')

@section('content')
    <h1 style="margin:0 0 16px; font-size:22px; font-weight:800; color:#0f172a;">Welcome to the Team, {{ $user->name }}!</h1>
    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        An admin account has been created for you at {{ $siteSettings->site_name ?: 'AK Tech SOL' }}. You can now sign in to the admin panel
        with the details below.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
        <tr>
            <td style="padding:20px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#475569;">
                    <tr>
                        <td style="padding:4px 0; width:90px; color:#94a3b8;">Name</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Email</td>
                        <td style="padding:4px 0;">{{ $user->email }}</td>
                    </tr>
                    @if(!empty($password))
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Password</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $password }}</td>
                    </tr>
                    @endif
                    @if($user->roles->isNotEmpty())
                    <tr>
                        <td style="padding:4px 0; color:#94a3b8;">Role</td>
                        <td style="padding:4px 0; color:#0f172a; font-weight:600;">{{ $user->roles->pluck('name')->join(', ') }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 16px; font-size:15px; color:#475569; line-height:1.7;">
        @if(!empty($password))
        Sign in with the email and password above. If you ever forget your password,
        you can reset it anytime from the login page using
        <strong style="color:#0f172a;">Forgot password</strong>.
        @else
        Your password has been set by your administrator — please ask them for it.
        If you ever forget it, you can reset it anytime from the login page using
        <strong style="color:#0f172a;">Forgot password</strong>.
        @endif
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
        <tr>
            <td align="center">
                <a href="{{ route('login') }}"
                   style="display:inline-block; padding:14px 40px; background:linear-gradient(135deg, #1d4ed8, #2f6fed); color:#ffffff; font-size:15px; font-weight:700; text-decoration:none; border-radius:12px;">
                    Sign In to Admin Panel
                </a>
            </td>
        </tr>
    </table>
@endsection
