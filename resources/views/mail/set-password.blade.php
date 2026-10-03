<!DOCTYPE html>
<html lang="en">
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h1 style="font-size: 20px;">Hello {{ $name }}</h1>
    <p>Use this token to set your password:</p>
    <p style="font-family: monospace; font-size: 15px; background: #f3f4f6; padding: 12px;">{{ $token }}</p>
    <p>Send it to <code>POST /api/v1/auth/reset-password</code> with your email (<code>{{ $email }}</code>),
        a new <code>password</code>, and <code>password_confirmation</code>.
        The token expires in {{ config('auth.passwords.users.expire') }} minutes.</p>
    <p>If you did not expect this email, you can ignore it.</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
