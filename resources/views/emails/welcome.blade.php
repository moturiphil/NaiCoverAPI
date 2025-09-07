<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to InsureMore</title>
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
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to InsureMore!</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }}!</h2>
        
        <p>Welcome to InsureMore, your trusted insurance partner.</p>
        
        <p>We are excited to have you on board and look forward to providing you with the best insurance solutions tailored to your needs.</p>
        
        <p><strong>Your account details:</strong></p>
        <ul>
            <li>Email: {{ $user->email }}</li>
            <li>Registration Date: {{ $user->created_at->format('F j, Y') }}</li>
        </ul>
        
        <a href="{{ url('/dashboard') }}" class="button">Get Started</a>
        
        <p>If you have any questions, feel free to contact our support team at support@insuremore.com or call us at 1-800-INSURE-MORE.</p>
        
        <p>Thank you for choosing InsureMore!</p>
        
        <p>Best regards,<br>
        The InsureMore Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} InsureMore. All rights reserved.</p>
        <p>This email was sent to {{ $user->email }}</p>
    </div>
</body>
</html>