<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="background-color: #f8f9fa; border-radius: 10px; padding: 30px; margin-bottom: 20px;">
        <h2 style="color: #0d6efd; margin-top: 0;">New Contact Form Submission</h2>
        <p style="font-size: 16px; color: #666;">You have received a new message from your website contact form.</p>
    </div>

    <div style="background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; padding: 30px;">
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    <strong style="color: #0d6efd;">Name:</strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    {{ $name }}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    <strong style="color: #0d6efd;">Email:</strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    <a href="mailto:{{ $email }}" style="color: #0d6efd; text-decoration: none;">{{ $email }}</a>
                </td>
            </tr>
            @if(isset($phone) && $phone)
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    <strong style="color: #0d6efd;">Phone:</strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    {{ $phone }}
                </td>
            </tr>
            @endif
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    <strong style="color: #0d6efd;">Subject:</strong>
                </td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e0e0e0;">
                    {{ $subject }}
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px;">
            <strong style="color: #0d6efd; display: block; margin-bottom: 10px;">Message:</strong>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; border-left: 4px solid #0d6efd;">
                {!! nl2br(e($message)) !!}
            </div>
        </div>

    </div>

    <div style="margin-top: 30px; text-align: center; color: #999; font-size: 14px;">
        <p>This email was sent from your {{ settings('site_name', 'VeCarCMS') }} contact form</p>
        <p style="margin-top: 10px;">
            <a href="{{ url('/admin') }}" style="color: #0d6efd; text-decoration: none;">Go to Admin Panel</a>
        </p>
    </div>

</body>
</html>

