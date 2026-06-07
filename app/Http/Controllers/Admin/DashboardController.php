<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menyiapkan ringkasan pesanan, pendapatan, dan data grafik dashboard admin.
     */
    public function index()
    {
        $revenue      = Pesanan::where('status', 'selesai')->sum('total_harga');
        $orders       = Pesanan::with('paket')->latest()->take(10)->get();
        $totalPaket   = Paket::count();
        $totalPesanan = Pesanan::count();

        // ── Chart 1: Tren Pendapatan Bulanan (12 bulan, tahun berjalan) ──────
        // Ambil sum total_harga per bulan dari pesanan berstatus 'selesai'
        $tahun = now()->year;

        $monthExpression = DB::getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', created_at) AS INTEGER)"
            : 'MONTH(created_at)';

        $pendapatanRaw = Pesanan::selectRaw("{$monthExpression} as bulan, SUM(total_harga) as total")
            ->where('status', 'selesai')
            ->whereYear('created_at', $tahun)
            ->groupBy('bulan')
            ->pluck('total', 'bulan'); // [bulan => total]

        // Isi semua 12 bulan, bulan yang tidak ada data = 0
        $chartBulan  = [];
        $chartRevenu = [];
        $namaBulan   = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($m = 1; $m <= 12; $m++) {
            $chartBulan[]  = $namaBulan[$m - 1];
            $chartRevenu[] = (int) ($pendapatanRaw[$m] ?? 0);
        }

        // ── Chart 2: Pendapatan per Paket (top 5, status selesai) ────────────
        $pendapatanPerPaket = Pesanan::selectRaw('id_paket, SUM(total_harga) as total')
            ->where('status', 'selesai')
            ->whereNotNull('id_paket')
            ->groupBy('id_paket')
            ->with('paket:id,nama_paket')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $chartPaketLabel = $pendapatanPerPaket->map(fn($p) => $p->paket->nama_paket ?? 'Paket #' . $p->id_paket)->values()->toArray();
        $chartPaketData  = $pendapatanPerPaket->map(fn($p) => (int) $p->total)->values()->toArray();

        return view('admin.index', compact(
            'revenue',
            'orders',
            'totalPaket',
            'totalPesanan',
            'chartBulan',
            'chartRevenu',
            'chartPaketLabel',
            'chartPaketData',
            'tahun'
        ));
    }
}
