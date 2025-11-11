<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting us</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; padding: 40px; text-align: center; margin-bottom: 30px;">
        <h1 style="color: #ffffff; margin: 0 0 10px 0; font-size: 32px;">Thank You!</h1>
        <p style="color: #ffffff; font-size: 18px; margin: 0; opacity: 0.9;">We've received your message</p>
    </div>

    <div style="background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; padding: 30px;">
        
        <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
            Hi <strong>{{ $name }}</strong>,
        </p>

        <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
            Thank you for reaching out to us! We have received your message and will get back to you as soon as possible.
        </p>

        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; border-left: 4px solid #0d6efd; margin: 30px 0;">
            <p style="margin: 0 0 10px 0; color: #999; font-size: 14px;">Your message:</p>
            <p style="margin: 0; font-size: 14px; color: #666;">{!! nl2br(e(str($message)->limit(200))) !!}</p>
        </div>

        <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
            Our team typically responds within 24-48 hours during business days.
        </p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/') }}" style="display: inline-block; background-color: #0d6efd; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 5px; font-weight: bold;">
                Visit Our Website
            </a>
        </div>

    </div>

    <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
        <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">
            <strong>{{ settings('site_name', 'VeCarCMS') }}</strong>
        </p>
        @if(settings('contact_email'))
        <p style="margin: 0 0 5px 0; font-size: 14px; color: #666;">
            <strong>Email:</strong> <a href="mailto:{{ settings('contact_email') }}" style="color: #0d6efd; text-decoration: none;">{{ settings('contact_email') }}</a>
        </p>
        @endif
        @if(settings('contact_phone'))
        <p style="margin: 0 0 5px 0; font-size: 14px; color: #666;">
            <strong>Phone:</strong> {{ settings('contact_phone') }}
        </p>
        @endif
        @if(settings('contact_address'))
        <p style="margin: 0; font-size: 14px; color: #666;">
            <strong>Address:</strong> {!! nl2br(e(settings('contact_address'))) !!}
        </p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; color: #999; font-size: 12px;">
        <p>This is an automated confirmation email. Please do not reply to this message.</p>
    </div>

</body>
</html>

