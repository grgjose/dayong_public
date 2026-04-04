<!DOCTYPE html>
<html>
<head>
    <title>TQMP | Forgot Password</title>
</head>
<body>
    <h1>Dear {{ $data['name'] }},</h1>
    <p>{{ $data['message'] }}</p>
    <p>Reset Password Link: <a href="{{ config('app.url') }}reset-password/{{ $data['token'] }}">Password Reset Link<a></p>
    <p>If you did not request for this password, Please delete this email from the inbox and trash bin <br> then, secure your account or change your password immediately.</p>
    <p>For any questions, feel free to contact our support team at <a href="mailto:support@dayong.gissolve.com">support@dayong.gissolve.com</a>.</p>
    <p>Best Regards</p>
    <p>Dayong App Team</p>
    <a href="{{ config('app.url') }}">Dayong Website</p>
</body>
</html>