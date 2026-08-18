<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        // كل منتج معرّف بخصائصه الخاصة فيه (أو بدون خصائص أصلاً)
        $products = [
            [
                'name' => 'قميص قطن رجالي',
                'attributes' => [
                    'اللون' => ['أحمر' => '#DC2626', 'أزرق' => '#2563EB', 'أسود' => '#0F172A'],
                    'المقاس' => ['S', 'M', 'L', 'XL'],
                ],
            ],
            [
                'name' => 'سماعة بلوتوث لاسلكية',
                'attributes' => [
                    'اللون' => ['أبيض' => '#F8FAFC', 'أسود' => '#0F172A'],
                ],
            ],
            [
                'name' => 'هاتف ذكي',
                'attributes' => [
                    'اللون' => ['ذهبي' => '#EAB308', 'فضي' => '#94A3B8', 'أسود' => '#0F172A'],
                    'السعة التخزينية' => ['128GB', '256GB', '512GB'],
                ],
            ],
            [
                'name' => 'رواية أدبية مترجمة',
                'attributes' => [], // بدون أي خصائص، منتج بسيط عادي
            ],
            [
                'name' => 'حذاء رياضي',
                'attributes' => [
                    'المقاس' => ['40', '41', '42', '43', '44'],
                ],
            ],
            [
                'name' => 'طقم أواني طبخ',
                'attributes' => [
                    'عدد القطع' => ['5 قطع', '10 قطع', '15 قطعة'],
                ],
            ],
            [
                'name' => 'كرة قدم احترافية',
                'attributes' => [], // بدون خصائص
            ],
            [
                'name' => 'لعبة تركيب للأطفال',
                'attributes' => [
                    'الفئة العمرية' => ['3-5 سنوات', '6-8 سنوات', '9+ سنوات'],
                ],
            ],
        ];

        foreach ($products as $index => $productData) {
            $hasSale = $index % 3 === 0;
            $price = rand(50, 500);
            $mainImage = $this->downloadImage('products', 600, 600, $index);

            $product = Product::create([
                'category_id' => $categories->random()->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']) . '-' . $index,
                'description' => 'منتج عالي الجودة، مناسب للاستخدام اليومي، بتصميم عصري وخامات ممتازة تدوم طويلًا.',
                'price' => $price,
                'sale_price' => $hasSale ? round($price * 0.8) : null,
                'sale_starts_at' => $hasSale ? now()->subDay() : null,
                'sale_ends_at' => $hasSale ? now()->addDays(15) : null,
                'stock' => rand(10, 50),
                'main_image' => $mainImage,
                'is_active' => true,
            ]);

            // فئات إضافية
            $extraCategories = $categories->where('id', '!=', $product->category_id)->random(min(2, $categories->count() - 1));
            $product->categories()->attach($extraCategories->pluck('id'));

            // صور إضافية (Gallery)
            for ($i = 1; $i <= 3; $i++) {
                $galleryImage = $this->downloadImage('products/gallery', 600, 600, "{$index}-{$i}");
                if ($galleryImage) {
                    ProductImage::create(['product_id' => $product->id, 'image' => $galleryImage]);
                }
            }

            // لو المنتج بدون خصائص، خلص هون
            if (empty($productData['attributes'])) {
                continue;
            }

            // أنشئ الخصائص وقيمها
            $attributeValueGroups = [];

            $order = 0;
            foreach ($productData['attributes'] as $attrName => $values) {
                $attribute = $product->attributes()->create([
                    'name' => $attrName,
                    'order' => $order++,
                ]);

                $valueModels = [];
                $valOrder = 0;
                foreach ($values as $key => $val) {
                    if (is_string($key)) {
                        $valueModels[] = $attribute->values()->create([
                            'value' => $key,
                            'hex_code' => $val,
                            'order' => $valOrder++,
                        ]);
                    } else {
                        $valueModels[] = $attribute->values()->create([
                            'value' => $val,
                            'order' => $valOrder++,
                        ]);
                    }
                }

                $attributeValueGroups[] = $valueModels;
            }

            // أنشئ كل التركيبات الممكنة (Cartesian Product) كـ Variants
            $combinations = $this->cartesianProduct($attributeValueGroups);

            foreach ($combinations as $combination) {
                $variant = $product->variants()->create([
                    'sku' => strtoupper(Str::random(8)),
                    'stock' => rand(0, 20),
                    'price_adjustment' => 0,
                ]);

                foreach ($combination as $value) {
                    $variant->attributeValues()->attach($value->id);
                }
            }
        }
    }

    private function cartesianProduct(array $arrays): array
    {
        if (empty($arrays)) {
            return [];
        }

        $result = [[]];

        foreach ($arrays as $propertyValues) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $temp[] = array_merge($resultItem, [$propertyValue]);
                }
            }
            $result = $temp;
        }

        return $result;
    }

    private function downloadImage(string $folder, int $width, int $height, $seed): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://picsum.photos/seed/" . str_replace('/', '-', $folder) . "{$seed}/{$width}/{$height}");

            if ($response->successful()) {
                $filename = "{$folder}/img-{$seed}.jpg";
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
        }

        return null;
    }
}