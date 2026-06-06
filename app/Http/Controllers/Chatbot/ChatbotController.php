<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Services\Chatbot\GeminiChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ChatbotController extends Controller
{
    /**
     * Menyuntikkan layanan chatbot yang menangani percakapan publik.
     */
    public function __construct(private readonly GeminiChatbotService $chatbot) {}

    /**
     * Memvalidasi pesan pelanggan dan mengembalikan jawaban chatbot dalam JSON.
     */
    public function handlePublicMessage(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'message' => ['required', 'string', 'max:2000'],
            ]);

            return response()->json([
                'success' => true,
                'response' => $this->chatbot->reply($validated['message']),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'response' => 'Mohon masukkan pesan yang valid.',
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Public chatbot error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan pada sistem. Silakan coba lagi nanti.',
            ], 500);
        }
    }

    /**
     * Mengembalikan pesan pembuka dan pilihan cepat chatbot.
     */
    public function getPublicMenu(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'response' => "Halo! Selamat datang di **Ghina Tour Travel**.\n\nSaya customer service AI Ghina Tour Travel. Saya bisa bantu informasi paket wisata, harga, fasilitas, itinerary, kontak admin, dan status pesanan.",
            'options' => ['Rekomendasi paket', 'Paket termurah', 'Cek status pesanan', 'Kontak admin'],
        ]);
    }
}
