<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            'شركة التقنية المتقدمة', 'مؤسسة التوريدات الذهبية', 'شركة الشحن السريع',
            'مجموعة الأعمال الحديثة', 'شركة الإبداع للتسويق', 'مصنع المستقبل',
        ];

        foreach ($partners as $index => $name) {
            $logoPath = $this->downloadLogo($index);

            Partner::create([
                'company_name' => $name,
                'contact_name' => 'مسؤول التواصل',
                'email' => 'partner' . $index . '@example.com',
                'phone' => '05900000' . $index,
                'logo' => $logoPath,
                'status' => 'approved',
                'show_on_site' => true,
                'reviewed_at' => now(),
            ]);
        }
    }

    private function downloadLogo(int $seed): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://picsum.photos/seed/partner{$seed}/300/150");

            if ($response->successful()) {
                $filename = "partners/partner-{$seed}.jpg";
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
        }

        return null;
    }
}