<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background: #F8FAFC; padding: 30px; margin: 0;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <div style="background: #0F172A; padding: 25px; text-align: center;">
            <h1 style="color: #F97316; margin: 0; font-size: 24px;">سوق</h1>
        </div>
        <div style="padding: 30px;">
            <h2 style="color: #0F172A; margin-top: 0;">مرحبًا {{ $partner->contact_name }}! 🎉</h2>
            <p style="color: #475569; line-height: 1.8;">
                يسعدنا إبلاغكم بأنه تمت الموافقة على طلب شراكة "<strong>{{ $partner->company_name }}</strong>" مع سوق.
                رح يظهر شعار شركتكم قريبًا ضمن شركاء النجاح بموقعنا.
            </p>
            <p style="color: #475569; line-height: 1.8;">
                نتطلع لتعاون مثمر بيننا، ولأي استفسار لا تترددوا بالتواصل معنا.
            </p>
            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('about') }}" style="display: inline-block; background: #F97316; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none;">
                    زيارة الموقع
                </a>
            </div>
        </div>
        <div style="background: #F8FAFC; padding: 15px; text-align: center; border-top: 1px solid #E2E8F0;">
            <p style="color: #94A3B8; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} سوق. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>