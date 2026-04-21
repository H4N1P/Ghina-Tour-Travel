<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\AI\Agents\CustomerSupportAgent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot messages using AI Agent
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function handleMessage(Request $request)
    {
        try {
            $userMessage = trim($request->input('message', ''));

            if (empty($userMessage)) {
                return response()->json([
                    'success' => false,
                    'response' => 'Mohon masukkan pesan Anda.'
                ]);
            }

            // Create the AI Agent
            $agent = new CustomerSupportAgent();

            // Process the message through the agent (AI will use tools as needed)
            $response = $agent->send($userMessage);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan pada sistem. Silakan coba lagi nanti.'
            ]);
        }
    }

    /**
     * Get initial greeting menu
     * 
     * @return JsonResponse
     */
    public function getMenu()
    {
        try {
            $agent = new CustomerSupportAgent();
            
            // Use the getMenu tool directly
            $menuText = $agent->send('tampilkan menu utama');

            return response()->json([
                'success' => true,
                'response' => $menuText,
                'options' => ['paket', 'pesanan', 'company profile']
            ]);

        } catch (\Exception $e) {
            Log::error('Get Menu Error: ' . $e->getMessage());

            return response()->json([
                'success' => true,
                'response' => "Halo! Selamat datang di Ghina Tour Travel! 😊\n\n" .
                              "Saya asisten virtual yang siap membantu Anda:\n\n" .
                              "📦 **Paket Tour** - Lihat daftar paket tour kami\n" .
                              "📋 **Pesanan** - Cek status pesanan Anda\n" .
                              "🏢 **Profil Perusahaan** - Info tentang kami\n\n" .
                              "Silakan ketik menu yang Anda inginkan atau ajukan pertanyaan langsung!",
                'options' => ['paket', 'pesanan', 'company profile']
            ]);
        }
    }

    /**
     * Get all paket packages using AI Agent tool
     * 
     * @return JsonResponse
     */
    public function getPakets()
    {
        try {
            $agent = new CustomerSupportAgent();
            
            $response = $agent->send('tampilkan semua paket tour yang tersedia');

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Get Pakets Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'response' => 'Terjadi kesalahan saat mengambil data paket tour.'
            ]);
        }
    }

    /**
     * Get company profile using AI Agent tool
     * 
     * @return JsonResponse
     */
    public function getCompanyProfile()
    {
        try {
            $agent = new CustomerSupportAgent();
            
            $response = $agent->send('tampilkan profil perusahaan');

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Get Company Profile Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'response' => 'Terjadi kesalahan saat mengambil profil perusahaan.'
            ]);
        }
    }

    /**
     * Search pesanan by phone number
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPesanan(Request $request)
    {
        try {
            $phoneNumber = $request->input('phone', '');

            if (empty($phoneNumber)) {
                return response()->json([
                    'success' => false,
                    'response' => 'Mohon masukkan nomor HP untuk pencarian.'
                ]);
            }

            $agent = new CustomerSupportAgent();
            
            $response = $agent->send("cari pesanan dengan nomor HP: {$phoneNumber}");

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Search Pesanan Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'response' => 'Terjadi kesalahan saat mencari pesanan.'
            ]);
        }
    }
}
