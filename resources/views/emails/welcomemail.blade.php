<!DOCTYPE html>
<html>

<head>
    <title>Welcome to CareerBridge!</title>
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
                <h2 style="color: #4CAF50;">Welcome to CareerBridge!</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px;">
                <p>Hello {{ $user->username }},</p>
                <p>Thank you for joining CareerBridge, the professional platform where connections and career growth
                    meet. We’re excited to have you on board and are here to help you make the most out of your journey!
                </p>

                <p>Here are some things you can do to get started:</p>
                <ul>
                    <li>Build your professional profile and showcase your skills.</li>
                    <li>Explore job opportunities that match your expertise.</li>
                    <li>Connect with industry leaders and grow your network.</li>
                </ul>

                <p>If you have any questions or need support, our team is here for you. Just reply to this email or
                    contact us at <a href="mailto:hello@careerbridge.com">hello@careerbridge.com</a>.</p>

                <p>Welcome to the bridge to your future,</p>
                <p>The CareerBridge Team</p>
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
