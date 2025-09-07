Welcome to InsureMore!

Hello {{ $userName }}!

Welcome to InsureMore, your trusted insurance partner.

We are excited to have you on board and look forward to providing you with the best insurance solutions tailored to your needs.

Your account details:
- Email: {{ $user->email }}
- Registration Date: {{ $user->created_at->format('F j, Y') }}

Get Started: {{ url('/dashboard') }}

If you have any questions, feel free to contact our support team at support@insuremore.com or call us at 1-800-INSURE-MORE.

Thank you for choosing InsureMore!

Best regards,
The InsureMore Team

© {{ date('Y') }} InsureMore. All rights reserved.
This email was sent to {{ $user->email }}