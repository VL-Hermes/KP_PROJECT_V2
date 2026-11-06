<?php

namespace App\Services;

use App\Models\Laptop;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LaptopAiAdvisorService
{
    /**
     * Generate an AI recommendation based on the user's inquiry and live inventory.
     *
     * @param string $message
     * @param array $history
     * @return array
     */
    public function getRecommendation(string $message, array $history = []): array
    {
        $availableLaptops = Laptop::available()->get();

        if ($availableLaptops->isEmpty()) {
            return [
                'reply' => "Saat ini belum ada laptop yang berstatus tersedia di inventaris kami. Silakan cek kembali nanti atau hubungi admin kami untuk update stok terbaru!",
                'source' => 'system',
            ];
        }

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-1.5-flash');

        if (!empty($apiKey)) {
            try {
                $geminiResponse = $this->callGemini($apiKey, $model, $message, $history, $availableLaptops);
                if ($geminiResponse) {
                    return [
                        'reply' => $geminiResponse,
                        'source' => 'gemini',
                        'model' => $model,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini AI API call failed, falling back to local advisor: ' . $e->getMessage());
            }
        }

        // Fallback local smart matcher (works without API key)
        $fallbackReply = $this->localRuleBasedRecommendation($message, $availableLaptops);
        $note = empty($apiKey)
            ? "\n\n*(Catatan: Rekomendasi di atas dibuat menggunakan sistem pencocokan lokal. Untuk mengaktifkan respon AI alami Google Gemini, cantumkan `GEMINI_API_KEY` pada file `.env`)*"
            : "";

        return [
            'reply' => $fallbackReply . $note,
            'source' => 'local_advisor',
        ];
    }

    /**
     * Call the Google Gemini generateContent API.
     */
    protected function callGemini(string $apiKey, string $model, string $userMessage, array $history, $laptops): ?string
    {
        $systemInstruction = $this->buildSystemInstruction($laptops);

        // Build contents history
        $contents = [];

        // Sanitize and include recent conversation history (max 8 messages for context)
        $recentHistory = array_slice($history, -8);
        foreach ($recentHistory as $item) {
            $role = ($item['role'] ?? 'user') === 'model' || ($item['role'] ?? 'user') === 'assistant' ? 'model' : 'user';
            $text = trim($item['text'] ?? $item['content'] ?? '');
            if (!empty($text)) {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]],
                ];
            }
        }

        // Add current user prompt
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1200,
            ],
        ];

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::timeout(15)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, $payload);

        if ($response->successful()) {
            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($reply) {
                return trim($reply);
            }
        } else {
            Log::warning('Gemini API returned error: ' . $response->status() . ' - ' . $response->body());
        }

        return null;
    }

    /**
     * Build system prompt instructions with live inventory list.
     */
    protected function buildSystemInstruction($laptops): string
    {
        $inventoryText = "";
        foreach ($laptops as $laptop) {
            $url = route('catalog.show', $laptop);
            $battery = filled($laptop->battery_health) ? $laptop->battery_health : 'Tidak dicantumkan';
            $condition = filled($laptop->physical_condition) ? $laptop->physical_condition : 'Kondisi standar';
            $price = $laptop->hasPriceEstimate() ? $laptop->formatted_price : 'Hubungi penjual';

            $inventoryText .= <<<EOT
- [ID: {$laptop->id}] {$laptop->brand} {$laptop->model}
  Price: {$price}
  CPU: {$laptop->processor_cpu}
  RAM: {$laptop->memory_ram}
  Storage: {$laptop->storage}
  GPU: {$laptop->graphics_gpu}
  Display: {$laptop->display}
  Battery Health: {$battery}
  Condition: {$condition}
  Catalog URL: {$url}

EOT;
        }

        return <<<EOT
You are "LaptopHub AI Advisor", an enthusiastic and honest hardware expert assistant on the LaptopHub used laptop catalog web platform.
Your objective is to help shoppers find the best pre-owned laptop matching their requirements (e.g. coding, office work, university studies, graphic design, portability, budget).

CURRENT IN-STOCK LAPTOPS IN STORE:
{$inventoryText}

CRITICAL RULES:
1. Recommend ONLY laptops that are listed in the CURRENT IN-STOCK LAPTOPS above. Never fabricate or recommend models not in our inventory.
2. For every laptop you recommend, always include its clickable catalog link using markdown: [Lihat Spesifikasi Lengkap Brand Model](/catalog/ID).
3. Be completely honest and transparent about their capabilities:
   - If a customer asks for heavy AAA gaming or complex 3D rendering and the inventory only has integrated graphics, politely clarify the limitation and suggest what it CAN handle (e.g. esports/casual games).
   - Highlight practical aspects like RAM capacity, battery health, and physical condition.
4. Language matching:
   - If the user writes in Indonesian, respond in friendly, professional Indonesian.
   - If the user writes in English, respond in English.
5. Format your answers clearly using bullet points, bold highlights, and readable structure so it looks great inside a chat popup. Keep answers concise and helpful.
EOT;
    }

    /**
     * Local rule-based recommendation fallback when Gemini API key is not configured.
     */
    protected function localRuleBasedRecommendation(string $query, $laptops): string
    {
        $q = strtolower($query);

        // Score laptops based on matching keywords
        $scored = $laptops->map(function ($laptop) use ($q) {
            $score = 0;
            $text = strtolower(implode(' ', [
                $laptop->brand,
                $laptop->model,
                $laptop->processor_cpu,
                $laptop->memory_ram,
                $laptop->storage,
                $laptop->graphics_gpu,
                $laptop->display,
                $laptop->battery_health ?? '',
                $laptop->physical_condition ?? '',
            ]));

            // Developer / Coding intent
            if (str_contains($q, 'koding') || str_contains($q, 'coding') || str_contains($q, 'program') || str_contains($q, 'developer')) {
                if (str_contains($text, '16 gb') || str_contains($text, '32 gb')) $score += 5;
                if (str_contains($text, 'i7') || str_contains($text, 'i5') || str_contains($text, 'ryzen')) $score += 3;
                if (str_contains($text, 'ssd') || str_contains($text, 'nvme')) $score += 2;
            }

            // Student / College / Office
            if (str_contains($q, 'kuliah') || str_contains($q, 'mahasiswa') || str_contains($q, 'office') || str_contains($q, 'sekolah') || str_contains($q, 'kerja')) {
                if (str_contains($text, 'thinkpad') || str_contains($text, 'latitude') || str_contains($text, 'elitebook')) $score += 4;
                if (str_contains($text, 'good') || str_contains($text, 'baterai') || str_contains($text, 'battery')) $score += 3;
                $score += 2;
            }

            // Battery priority
            if (str_contains($q, 'baterai') || str_contains($q, 'battery') || str_contains($q, 'awet')) {
                if (str_contains(strtolower($laptop->battery_health ?? ''), 'good') || str_contains(strtolower($laptop->battery_health ?? ''), '100%')) $score += 6;
            }

            // Brand mentions
            foreach (['lenovo', 'thinkpad', 'dell', 'hp', 'asus', 'acer', 'apple', 'macbook'] as $brand) {
                if (str_contains($q, $brand) && str_contains($text, $brand)) {
                    $score += 6;
                }
            }

            // RAM mentions
            if ((str_contains($q, '16gb') || str_contains($q, '16 gb')) && str_contains($text, '16 gb')) $score += 6;
            if ((str_contains($q, '8gb') || str_contains($q, '8 gb')) && str_contains($text, '8 gb')) $score += 5;

            // SSD / Storage mentions
            if ((str_contains($q, '512') || str_contains($q, '512gb')) && str_contains($text, '512')) $score += 5;

            return ['laptop' => $laptop, 'score' => $score];
        });

        $topLaptops = $scored->sortByDesc('score')->take(2);

        if ($topLaptops->isEmpty()) {
            return "Halo! Kami siap membantu Anda memilih laptop terbaik. Silakan sebutkan kebutuhan Anda (misal: untuk kuliah, koding, kerja kantoran, atau budget spesifik)!";
        }

        $reply = "Halo! Berdasarkan stok laptop bekas kami saat ini, berikut adalah rekomendasi yang sangat cocok untuk Anda:\n\n";

        foreach ($topLaptops as $item) {
            $l = $item['laptop'];
            $url = route('catalog.show', $l);
            $battery = filled($l->battery_health) ? "• **Baterai:** {$l->battery_health}\n" : "";
            $price = $l->hasPriceEstimate() ? "• **Estimasi Harga:** {$l->formatted_price}\n" : "";

            $reply .= "### 💻 [{$l->brand} {$l->model}]({$url})\n";
            if ($price) $reply .= $price;
            $reply .= "• **Processor:** {$l->processor_cpu}\n";
            $reply .= "• **RAM & Storage:** {$l->memory_ram} | {$l->storage}\n";
            $reply .= "• **Display & GPU:** {$l->display} | {$l->graphics_gpu}\n";
            if ($battery) $reply .= $battery;
            $reply .= "👉 [Lihat Spesifikasi & Diagnostik Lengkap]({$url})\n\n";
        }

        $reply .= "Apakah ada kriteria spesifik lain yang Anda cari seperti daya tahan baterai, bobot ringan, atau kapasitas RAM tertentu?";

        return $reply;
    }
}

