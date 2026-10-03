<!DOCTYPE html>
<html lang="en">
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h1 style="font-size: 20px;">Welcome, {{ $ownerName }}</h1>
    <p>Your company <strong>{{ $companyName }}</strong> is ready on {{ config('app.name') }}.</p>
    <p>You are the owner of this account. You can now add your team and your customers through the API.</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
