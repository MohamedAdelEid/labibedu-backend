<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your OTP Code</title>
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

        .otp-code {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 24px;
            text-align: center;
            margin: 20px 0;
            letter-spacing: 5px;
            border: 2px solid #007bff;
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

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Your OTP Code</h1>
    </div>

    <div class="content">
        <p>Hello,</p>

        <p>You are receiving this email because you requested an OTP (One-Time Password) for your account.</p>

        <p>Your OTP code is:</p>

        <div class="otp-code">
            <strong>{{ $otp }}</strong>
        </div>

        <div class="warning">
            <strong>Important:</strong> This code will expire in 10 minutes. Do not share this code with anyone.
        </div>

        <p>If you did not request this OTP, please ignore this email or contact our support team.</p>

        <p>Best regards,<br>
            LabibEdu Team</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} LabibEdu. All rights reserved.</p>
    </div>
</body>

</html>
