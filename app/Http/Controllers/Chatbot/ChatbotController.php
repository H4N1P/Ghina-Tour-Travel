<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Services\Chatbot\GeminiChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(private readonly GeminiChatbotService $chatbot)
    {
    }

    public function handlePublicMessage(Request $request): JsonResponse
    {
        return $this->handleAiMessage($request, 'public', 'Public Chatbot Error');
    }

    public function getPublicMenu(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => "Halo! Selamat datang di **Ghina Tour & Travel**.\n\nSaya adalah AI customer service yang bisa membantu tentang paket wisata, harga, fasilitas, kontak admin, dan status pesanan.",
            'options' => ['Rekomendasi Paket?', 'Paket Termurah', 'Tanya Admin'],
        ]);
    }

    public function handleMessage(Request $request): JsonResponse
    {
        return $this->handleAiMessage($request, 'admin', 'Chatbot Error');
    }

    public function getMenu(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => "Halo! Selamat datang di **Ghina Assistant**.\n\nSaya adalah AI customer service Ghina Tour Travel. Anda bisa bertanya tentang paket wisata, harga, fasilitas, kontak admin, atau status pesanan.\n\nContoh: *\"Cek pesanan dengan invoice INV-001\"* atau *\"Berapa harga paket ke Jogja?\"*",
            'options' => ['Paket Populer', 'Cara Cek Pesanan', 'Kontak Admin'],
        ]);
    }

<<<<<<< HEAD
    private function handleAiMessage(Request $request, string $audience, string $logContext): JsonResponse
=======
    /**
     * Core AI Integration Logic
     */
    private function callGemini(string $message): string
    {
        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return "Sistem AI sedang dalam perbaikan (API Key tidak ditemukan). Silakan hubungi admin via WhatsApp.";
        }

        // 1. Gather Context Data from Database
        $company = CompanyProfile::first();
        $pakets = Paket::with(['fasilitas', 'destinasis'])->get();

        // 2. Build the System Prompt (RAG - Retrieval Augmented Generation)
        $context = "Anda adalah Customer Service cerdas dari agen travel 'Ghina Tour Travel'.\n";
        $context .= "Jawab pertanyaan pelanggan dengan ramah, sopan, profesional, ringkas, dan persuasif.\n";
        $context .= "Gunakan emoji yang relevan secukupnya. Jangan pernah berhalusinasi atau mengarang harga/paket yang tidak ada di dalam daftar di bawah ini. Jika ditanya hal di luar konteks travel/paket, arahkan kembali dengan sopan ke layanan Ghina Tour Travel.\n\n";

        $context .= "--- PROFIL PERUSAHAAN ---\n";
        if ($company) {
            $context .= "WhatsApp Admin: " . ($company->whatsapp ?: 'Belum diatur') . "\n";
            $context .= "Alamat: " . ($company->address ?: 'Belum diatur') . "\n";
            $context .= "Tentang Kami: " . ($company->about ?: 'Agen tour dan travel terpercaya.') . "\n\n";
        }

        $context .= "--- DAFTAR PAKET TOUR TERSEDIA ---\n";
        if ($pakets->isEmpty()) {
            $context .= "Saat ini belum ada paket yang tersedia.\n";
        } else {
            foreach ($pakets as $p) {
                $context .= "- Paket: {$p->nama_paket}\n";
                $context .= "  Durasi: {$p->durasi}\n";
                $context .= "  Harga: Rp " . number_format($p->harga_paket, 0, ',', '.') . "\n";
                if ($p->destinasis->isNotEmpty()) {
                    $context .= "  Tujuan: " . implode(', ', $p->destinasis->pluck('nama_destinasi')->toArray()) . "\n";
                }
                if ($p->note) {
                    $context .= "  Catatan Khusus: {$p->note}\n";
                }
            }
        }

        $context .= "\n\n--- PERTANYAAN PELANGGAN ---\n";
        $context .= $message . "\n";
        $context .= "--- \nTuliskan balasan Anda sekarang:";

        // 3. Make the API Call to Google Gemini
        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $context]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya tidak dapat memproses respons saat ini.";
            }

            Log::error('Gemini API Error Response: ' . $response->body());
            return "Maaf, sistem AI sedang mengalami gangguan koneksi. Silakan coba beberapa saat lagi.";

        } catch (\Exception $e) {
            Log::error('Gemini Request Exception: ' . $e->getMessage());
            return "Maaf, terjadi kesalahan koneksi internal ke server AI.";
        }
    }

    /**
     * Handle pesanan search by phone number (Kept from old logic for accuracy)
     */
    private function handlePesananSearch(string $noHp): JsonResponse
>>>>>>> 670a427 (feat(gallery): implement global lightbox, admin category filters, drag-and-drop preview deletion, and refactor places to destinations)
    {
        try {
            $userMessage = trim((string) $request->input('message', ''));

            if ($userMessage === '') {
                return response()->json([
                    'success' => false,
                    'response' => 'Mohon masukkan pesan Anda.',
                ]);
            }

            return response()->json([
                'success' => true,
                'response' => $this->chatbot->reply($userMessage, $audience),
            ]);
        } catch (\Throwable $e) {
            Log::error($logContext . ': ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan pada sistem. Silakan coba lagi nanti.',
            ]);
        }
    }
}
