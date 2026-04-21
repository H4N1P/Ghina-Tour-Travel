<?php

namespace App\AI\Agents;

use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\CompanyProfile;
use Laravel\AI\Agent;
use Laravel\AI\Concerns\RemembersConversations;

class CustomerSupportAgent extends Agent
{
    use RemembersConversations;

    /**
     * Define the agent's instructions
     */
    public function instructions(): string
    {
        return "Kamu adalah asisten virtual untuk Ghina Tour Travel, sebuah perusahaan travel dan tour.
                Selalu jawab dalam Bahasa Indonesia yang sopan dan ramah.
                Gunakan tools yang tersedia untuk mengambil data terbaru dari database.
                Jika data tidak ditemukan, sampaikan dengan sopan bahwa informasi tidak tersedia.
                Berikan jawaban yang informatif dan membantu.";
    }

    /**
     * Define the tools available to the AI
     */
    public function tools(): array
    {
        return [
            // Tool untuk mencari paket tour
            'searchPaket' => function (string $query = null) {
                $queryBuilder = Paket::with(['fasilitas', 'tempats']);

                if ($query) {
                    $queryBuilder->where('nama_paket', 'like', "%{$query}%");
                }

                $pakets = $queryBuilder->get();

                if ($pakets->isEmpty()) {
                    return "Maaf, tidak ada paket tour yang ditemukan.";
                }

                $result = "DAFTAR PAKET TOUR:\n\n";
                foreach ($pakets as $index => $paket) {
                    $result .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                    $result .= ($index + 1) . ". {$paket->nama_paket}\n";
                    $result .= "   💰 Harga: Rp " . number_format($paket->harga_paket, 0, ',', '.') . "\n";
                    $result .= "   ⏱️ Durasi: {$paket->durasi}\n";
                    
                    if ($paket->fasilitas->isNotEmpty()) {
                        $fasilitas = $paket->fasilitas->pluck('nama_fasilitas')->toArray();
                        $result .= "   ✅ Fasilitas: " . implode(', ', $fasilitas) . "\n";
                    }
                    
                    if ($paket->tempats->isNotEmpty()) {
                        $tempats = $paket->tempats->pluck('nama_tempat')->toArray();
                        $result .= "   📍 Tujuan: " . implode(', ', $tempats) . "\n";
                    }
                    
                    if ($paket->note) {
                        $result .= "   📝 Note: {$paket->note}\n";
                    }
                    $result .= "\n";
                }

                return $result;
            },

            // Tool untuk mencari detail paket tertentu
            'getPaketDetail' => function (int $id) {
                $paket = Paket::with(['fasilitas', 'tempats'])->find($id);

                if (!$paket) {
                    return "Maaf, paket tidak ditemukan.";
                }

                $result = "DETAIL PAKET {$paket->nama_paket}:\n\n";
                $result .= "💰 Harga: Rp " . number_format($paket->harga_paket, 0, ',', '.') . "\n";
                $result .= "⏱️ Durasi: {$paket->durasi}\n";
                
                if ($paket->rundown) {
                    $result .= "\n📋 Rundown:\n{$paket->rundown}\n";
                }
                
                if ($paket->fasilitas->isNotEmpty()) {
                    $result .= "\n✅ Fasilitas:\n";
                    foreach ($paket->fasilitas as $fasilitas) {
                        $result .= "- {$fasilitas->nama_fasilitas}\n";
                    }
                }
                
                if ($paket->tempats->isNotEmpty()) {
                    $result .= "\n📍 Tempat Tujuan:\n";
                    foreach ($paket->tempats as $tempat) {
                        $result .= "- {$tempat->nama_tempat}\n";
                    }
                }
                
                if ($paket->note) {
                    $result .= "\n📝 Catatan: {$paket->note}\n";
                }

                return $result;
            },

            // Tool untuk mencari pesanan berdasarkan nomor HP
            'searchPesanan' => function (string $noHp = null) {
                if (!$noHp) {
                    return "Mohon berikan nomor HP untuk mencari pesanan.";
                }

                // Clean phone number
                $cleanNoHp = preg_replace('/[^0-9]/', '', $noHp);

                $pesanans = Pesanan::with('paket')
                    ->where('no_hp', 'like', '%' . $cleanNoHp . '%')
                    ->latest()
                    ->take(10)
                    ->get();

                if ($pesanans->isEmpty()) {
                    return "Maaf, tidak ada pesanan yang ditemukan dengan nomor HP tersebut. Pastikan nomor HP yang dimasukkan benar.";
                }

                $result = "DAFTAR PESANAN:\n\n";
                foreach ($pesanans as $index => $pesanan) {
                    $result .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                    $result .= "Pesanan #" . ($index + 1) . "\n";
                    $result .= "   👤 Pemesan: {$pesanan->nama_pemesan}\n";
                    $result .= "   📦 Paket: " . ($pesanan->paket ? $pesanan->paket->nama_paket : 'N/A') . "\n";
                    $result .= "   📅 Tanggal: " . \Carbon\Carbon::parse($pesanan->tanggal_acara)->format('d F Y') . "\n";
                    $result .= "   👥 Jumlah: {$pesanan->jumlah_orang} orang\n";
                    $result .= "   💰 Total: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n";
                    
                    if ($pesanan->invoice) {
                        $result .= "   📄 Invoice: {$pesanan->invoice}\n";
                    }
                    
                    $status = $pesanan->status ?? 'Menunggu Konfirmasi';
                    $result .= "   ✅ Status: {$status}\n";
                    $result .= "\n";
                }

                return $result;
            },

            // Tool untuk mendapatkan profil perusahaan
            'getCompanyProfile' => function () {
                $company = CompanyProfile::first();

                if (!$company) {
                    return "Maaf, informasi profil perusahaan belum tersedia.";
                }

                $result = "🏢 PROFIL GHINA TOUR TRAVEL\n\n";

                if ($company->about) {
                    $result .= "Tentang Kami:\n{$company->about}\n\n";
                }

                if ($company->vision_mission) {
                    $result .= "Visi & Misi:\n{$company->vision_mission}\n\n";
                }

                $result .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                $result .= "📞 KONTAK KAMI\n\n";

                if ($company->whatsapp) {
                    $result .= "📱 WhatsApp: {$company->whatsapp}\n";
                }
                if ($company->email) {
                    $result .= "📧 Email: {$company->email}\n";
                }
                if ($company->address) {
                    $result .= "📍 Alamat: {$company->address}\n";
                }
                if ($company->instagram) {
                    $result .= "📸 Instagram: {$company->instagram}\n";
                }

                return $result;
            },

            // Tool untuk menu utama
            'getMenu' => function () {
                return "MENU UTAMA:\n\n" .
                       "Silakan tanyakan tentang:\n" .
                       "📦 Paket Tour - untuk melihat daftar paket tour\n" .
                       "📋 Pesanan - untuk cek status pesanan (siapkan nomor HP)\n" .
                       "🏢 Profil Perusahaan - untuk info tentang kami\n\n" .
                       "Atau ajukan pertanyaan langsung, saya akan bantu semampunya! 😊";
            },
        ];
    }
}
