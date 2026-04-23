<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f4f6; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 480px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 30px rgba(0,0,0,0.06);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #f472b6, #ec4899); padding: 32px 40px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">Pink Bunny 🐰</h1>
                <p style="margin: 8px 0 0; font-size: 12px; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 2px;">Email Verification</p>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding: 40px;">
                <p style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #1a1a2e;">Hi {{ $userName }}! 👋</p>
                <p style="margin: 0 0 28px; font-size: 14px; color: #666; line-height: 1.6;">
                    Thanks for signing up! Use the code below to verify your email address and start your beauty journey with us.
                </p>

                <!-- Code Box -->
                <div style="background: #fdf2f8; border: 2px dashed #f472b6; border-radius: 16px; padding: 28px; text-align: center; margin-bottom: 28px;">
                    <p style="margin: 0 0 8px; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #ec4899; font-weight: 700;">Your verification code</p>
                    <div style="font-size: 36px; font-weight: 800; letter-spacing: 12px; color: #1a1a2e; font-family: 'Consolas', 'Monaco', monospace; padding: 8px 0;">{{ $code }}</div>
                    <p style="margin: 12px 0 0; font-size: 12px; color: #999;">This code expires in 15 minutes</p>
                </div>

                <p style="margin: 0 0 20px; font-size: 13px; color: #888; line-height: 1.6;">
                    If you didn't create a Pink Bunny account, please ignore this email — no action is needed.
                </p>

                <div style="border-top: 1px solid #f0f0f0; padding-top: 20px; text-align: center;">
                    <p style="margin: 0; font-size: 12px; color: #ccc;">
                        © {{ date('Y') }} Pink Bunny Beauty · All rights reserved
                    </p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
