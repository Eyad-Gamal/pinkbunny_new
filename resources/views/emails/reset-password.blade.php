<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f4f6; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 30px rgba(0,0,0,0.06);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #f472b6, #ec4899); padding: 32px 40px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">Pink Bunny 🐰</h1>
                <p style="margin: 8px 0 0; font-size: 12px; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 2px;">Password Reset</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding: 40px;">
                <p style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #1a1a2e;">Hi {{ $notifiable->name ?? 'Beautiful' }}! 👋</p>
                <p style="margin: 0 0 28px; font-size: 14px; color: #666; line-height: 1.6;">
                    You are receiving this email because we received a password reset request for your account. Don't worry, we'll get you back to shopping for your favorites in no time!
                </p>

                <!-- Button Box -->
                <div style="text-align: center; margin-bottom: 32px;">
                    <a href="{{ $url }}" style="display: inline-block; background-color: #f472b6; color: #ffffff; padding: 14px 32px; border-radius: 50px; text-decoration: none; font-size: 15px; font-weight: 700; box-shadow: 0 4px 14px rgba(244, 114, 182, 0.4);">
                        Reset Password
                    </a>
                </div>

                <p style="margin: 0 0 20px; font-size: 13px; color: #888; line-height: 1.6;">
                    This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.
                </p>

                <p style="margin: 0 0 20px; font-size: 13px; color: #888; line-height: 1.6;">
                    If you did not request a password reset, you can safely ignore this email — no changes will be made to your account.
                </p>

                <div style="border-top: 1px solid #f0f0f0; padding-top: 20px; text-align: center;">
                    <p style="margin: 0; font-size: 12px; color: #ccc;">
                        © {{ date('Y') }} Pink Bunny Beauty · All rights reserved
                    </p>
                </div>
            </td>
        </tr>
        <!-- Raw URL fallback -->
        <tr>
            <td style="padding: 0 40px 30px; background: #fafafa;">
                <p style="margin: 0; font-size: 11px; color: #bbb; line-height: 1.5; word-break: break-all;">
                    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br><br>
                    <a href="{{ $url }}" style="color: #f472b6; text-decoration: none;">{{ $url }}</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
