<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        /* Inline CSS for email clients */
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
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 20px;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{ config('app.name') }}, {{ $user->name }}!</h1>
    </div>
    
    <div class="content">
        <p>Thank you for joining our community. We're excited to have you on board!</p>
        
        <p>Here's your verification code:</p>
        
        <div style="background: #f8f9fa; padding: 15px; text-align: center; margin: 20px 0; 
                   font-size: 24px; font-weight: bold; letter-spacing: 2px;">
            {{ $code }}
        </div>
        
        <p>This code will expire in 24 hours.</p>
        
        <a href="{{ route('verification.verify') }}" class="button">
            Verify Your Account
        </a>
        
        <p>If you didn't request this, please ignore this email.</p>
    </div>
    
    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
        <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
    </div>
</body>
</html>