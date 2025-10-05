<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reset Your Password</h1>
    </div>

    <div class="content">
        <p>Hello,</p>

        <p>You are receiving this email because we received a password reset request for your account.</p>

        <p>Your password reset token is:</p>

        <div
            style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 18px; text-align: center; margin: 20px 0;">
            <strong>{{ $token }}</strong>
        </div>

        <p>This token will expire in 24 hours.</p>

        <p>If you did not request a password reset, no further action is required.</p>

        <p>Best regards,<br>
            LabibEdu Team</p>
    </div>

    <div class="footer">
        <p>This email was sent to {{ $email }}</p>
        <p>&copy; {{ date('Y') }} LabibEdu. All rights reserved.</p>
    </div>
</body>

</html>
