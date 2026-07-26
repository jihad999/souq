<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $productNames = [
            'سماعة بلوتوث لاسلكية', 'ساعة ذكية رياضية', 'حقيبة ظهر عصرية',
            'قميص قطن رجالي', 'طقم أواني طبخ', 'كرسي مكتب مريح',
            'كرة قدم احترافية', 'رواية أدبية مترجمة', 'لعبة تركيب للأطفال',
            'شاحن سريع USB-C', 'نظارة شمسية عصرية', 'حذاء رياضي',
        ];

        foreach ($productNames as $index => $name) {
            $hasSale = $index % 3 === 0;
            $price = rand(50, 500);
            $imagePath = $this->downloadImage('products', 600, 600, $index);

            Product::create([
                'category_id' => $categories->random()->id,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . $index,
                'description' => 'منتج عالي الجودة، مناسب للاستخدام اليومي، بتصميم عصري وخامات ممتازة تدوم طويلًا.',
                'price' => $price,
                'sale_price' => $hasSale ? round($price * 0.8) : null,
                'sale_starts_at' => $hasSale ? now()->subDay() : null,
                'sale_ends_at' => $hasSale ? now()->addDays(15) : null,
                'stock' => rand(0, 50),
                'main_image' => $imagePath,
                'is_active' => true,
            ]);
        }
    }

    private function downloadImage(string $folder, int $width, int $height, int $seed): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://picsum.photos/seed/{$folder}{$seed}/{$width}/{$height}");

            if ($response->successful()) {
                $filename = "{$folder}/{$folder}-{$seed}.jpg";
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            // لو ما في إنترنت وقت الـ seeding، بيرجع null وبتظهر "لا توجد صورة"
        }

        return null;
    }
}