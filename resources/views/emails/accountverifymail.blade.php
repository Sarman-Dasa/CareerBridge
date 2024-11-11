<!DOCTYPE html>
<html>

<head>
    <title>Verify Your Email - CareerBridge</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">

    <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <img src="{{ asset('logo.png') }}" alt="CareerBridge Logo" style="width: 150px;">
            </td>
        </tr>
        <tr>
            <td align="center">
                <h2 style="color: #4CAF50;">Verify Your Email</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px;">
                <p>Hello {{ $user->username }},</p>
                <p>Thank you for joining CareerBridge! Please verify your email address by clicking the button below.
                    This helps keep your account secure and ensures you have full access to all our features.</p>
                <p>
                    <a href="{{ url(env('APP_URL') . '/account-verify?token=' . $token . '&email=' . $user->email) }}"
                        style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #4CAF50; text-decoration: none; border-radius: 5px;">
                        Verify Email
                    </a>
                </p>

                <p>If you didn’t create an account, you can safely ignore this email.</p>

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
