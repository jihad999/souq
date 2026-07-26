<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            'كيف تختار المنتج المناسب لاحتياجاتك',
            '5 نصائح للتسوق الذكي أونلاين',
            'أهمية خدمة ما بعد البيع',
            'دليلك الشامل لعروض نهاية الموسم',
            'كيف تحافظ على منتجاتك لفترة أطول',
        ];

        foreach ($articles as $index => $title) {
            Article::create([
                'user_id' => null,
                'title' => $title,
                'slug' => Str::slug($title) . '-' . $index,
                'excerpt' => 'مقال يقدم نصائح ومعلومات مفيدة تساعدك على اتخاذ قرار شراء أفضل.',
                'content' => '<p>هذا محتوى تجريبي للمقال. يمكن استبداله بمحتوى حقيقي لاحقًا من لوحة التحكم.</p><p>الفقرة الثانية تتحدث بمزيد من التفصيل عن الموضوع.</p>',
                'is_published' => true,
                'published_at' => now()->subDays($index),
            ]);
        }
    }
}