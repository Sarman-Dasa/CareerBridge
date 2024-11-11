<!DOCTYPE html>
<html>

<head>
    <title>Reset Your Password - CareerBridge</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">

    <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <img src="{{ asset('images/logo.png') }}" alt="CareerBridge Logo" style="width: 150px;">
            </td>
        </tr>
        <tr>
            <td align="center">
                <h2 style="color: #4CAF50;">Reset Your Password</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px;">
                <p>Hello {{ $user->name }},</p>
                <p>We received a request to reset your password for your CareerBridge account. To reset your password,
                    click the button below:</p>

                <p>
                    <a href="{{ url(env('APP_URL') . '/reset-password?token=' . $user->token . '&email=' . $user->email) }}"
                        style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #4CAF50; text-decoration: none; border-radius: 5px;">
                        Reset Password
                    </a>
                </p>

                <p>This link will expire in 60 minutes. If you didn’t request a password reset, please ignore this
                    email, and your password will remain unchanged.</p>

                <p>Thank you,<br>The CareerBridge Team</p>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px;">
                <p style="font-size: 12px; color: #777;">
                    &copy; {{ date('Y') }} CareerBridge. All rights reserved.
                </p>
            </td>
        </tr>
    </table>

</body>

</html>
