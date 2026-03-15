@php
    $isRegistrationFlow = ($intent ?? 'login') === 'register';
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isRegistrationFlow ? 'Registration verification code' : 'Login verification code' }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;padding:24px;">
                <tr>
                    <td style="font-size:22px;font-weight:700;padding-bottom:12px;">{{ $appName }}</td>
                </tr>
                <tr>
                    <td style="font-size:16px;line-height:1.55;padding-bottom:16px;">
                        Enter this one-time code to {{ $isRegistrationFlow ? 'complete your registration' : 'complete your login' }}:
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding:8px 0 16px;">
                        <div style="display:inline-block;font-size:32px;font-weight:800;letter-spacing:6px;padding:14px 20px;background:#eef2ff;border-radius:10px;color:#1d4ed8;">
                            {{ $code }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;color:#374151;line-height:1.55;">
                        This code expires in {{ $expiresInMinutes }} minutes.<br>
                        If this was not you, you can safely ignore this email.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
