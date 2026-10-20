<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? ($siteSettings->site_name ?: 'AK Tech SOL') }}</title>
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!--<![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap');
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:'Sora', Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(15,42,92,0.08);">
                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #2f6fed 100%); padding:32px; text-align:center;">
                            <div style="font-size:24px; font-weight:800; color:#ffffff; letter-spacing:-0.5px;">{{ $siteSettings->site_name ?: 'AK Tech SOL' }}</div>
                            <div style="font-size:13px; color:rgba(255,255,255,0.75); margin-top:4px;">{{ $preheader ?? 'Digital Services Agency' }}</div>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            @yield('content')
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding:24px 32px; background:#f8fafc; border-top:1px solid #e2e8f0; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#94a3b8;">© {{ date('Y') }} {{ $siteSettings->site_name ?: 'AK Tech SOL' }}. All rights reserved.</p>
                            <p style="margin:8px 0 0; font-size:12px; color:#94a3b8;">This is an automated email. Please do not reply directly.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
