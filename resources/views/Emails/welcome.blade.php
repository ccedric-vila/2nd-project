<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for registering with us.</p>
    <p>Please click the link below to verify your email address:</p>
    
    <a href="{{ $verificationUrl }}">Verify Email Address</a>
    
    <p>If you did not create an account, no further action is required.</p>
</body>
</html>