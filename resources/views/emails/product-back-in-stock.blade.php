<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background: #F8FAFC; padding: 30px;">
    <div style="max-width: 500px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <div style="background: #0F172A; padding: 20px; text-align: center;">
            <h1 style="color: #F97316; margin: 0;">سوق</h1>
        </div>
        <div style="padding: 30px; text-align: center;">
            <h2 style="color: #0F172A;">المنتج صار متوفر! 🎉</h2>
            <p style="color: #475569; line-height: 1.8;">
                المنتج "<strong>{{ $product->name }}</strong>" اللي كنت بتنتظره صار متوفر بالمخزون هلأ.
                بادر بالطلب قبل ما تنفد الكمية مرة تانية.
            </p>
            <a href="{{ route('products.show', $product->slug) }}"
               style="display: inline-block; background: #F97316; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 15px;">
                اطلب الآن
            </a>
        </div>
    </div>
</body>
</html>