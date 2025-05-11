<!DOCTYPE html>
<html>
<head>
    <style>
        .email-box {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
        }
        .email-box h2 {
            color: #333;
        }
        .password-box {
            background: #fff3cd;
            padding: 10px;
            margin-top: 15px;
            font-weight: bold;
            color: #856404;
            border: 1px solid #ffeeba;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="email-box">
    <h2>Hello {{ $name }},</h2>
    <p>Your password has been successfully changed.</p>
    <p><strong>New Password:</strong></p>
    <div class="password-box">{{ $newPassword }}</div>
    <p>If you did not make this change, please contact support immediately.</p>
    <br>
    <p>Thanks,<br>The Team</p>
</div>
</body>
</html>
