<?php

namespace Database\Seeders;

use App\Models\ClientLogo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ClientLogoSeeder extends Seeder
{
    public function run(): void
    {
        $clients = ['شركة النور', 'مؤسسة الأمل', 'مجموعة الفا', 'شركة البناء الحديث', 'مصنع الجودة'];

        foreach ($clients as $index => $name) {
            $logoPath = $this->downloadLogo($index);

            ClientLogo::create([
                'name' => $name,
                'logo' => $logoPath,
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function downloadLogo(int $seed): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://picsum.photos/seed/client{$seed}/300/150");

            if ($response->successful()) {
                $filename = "clients/client-{$seed}.jpg";
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
        }

        return null;
    }
}