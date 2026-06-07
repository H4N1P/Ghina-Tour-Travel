<?php

use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

function createDashboardOrder(?Paket $paket, string $invoice, string $status, int $total, Carbon $createdAt): Pesanan
{
    $pesanan = Pesanan::create([
        'id_paket' => $paket?->id,
        'nama_pemesan' => "Pemesan {$invoice}",
        'no_hp' => '081234567890',
        'diskon' => 0,
        'jumlah_orang' => 2,
        'total_harga' => $total,
        'tanggal_acara' => $createdAt->toDateString(),
        'tanggal_selesai' => $createdAt->toDateString(),
        'invoice' => $invoice,
        'status' => $status,
        'is_custom' => $paket === null,
        'custom_places' => $paket === null ? ['Tujuan Custom'] : null,
    ]);

    $pesanan->timestamps = false;
    $pesanan->created_at = $createdAt;
    $pesanan->updated_at = $createdAt;
    $pesanan->save();

    return $pesanan;
}

it('uses all database orders for dashboard totals while limiting the latest order table', function () {
    $paket = Paket::create([
        'nama_paket' => 'Paket Dashboard',
        'harga_paket' => 100000,
        'durasi' => '1 Hari',
    ]);

    foreach (range(1, 12) as $index) {
        createDashboardOrder(
            $paket,
            "INV-DASH-{$index}",
            $index <= 7 ? 'selesai' : 'pending',
            100000,
            now()->subMinutes(13 - $index),
        );
    }

    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response
        ->assertOk()
        ->assertViewHas('totalPesanan', 12)
        ->assertViewHas('revenue', 700000)
        ->assertViewHas('orders', fn ($orders) => $orders->count() === 10);
});

it('builds yearly revenue from all completed orders and limits package revenue chart to top five', function () {
    Carbon::setTestNow(Carbon::create(2026, 6, 7, 12));

    $pakets = collect(range(1, 6))->map(fn ($index) => Paket::create([
        'nama_paket' => "Paket {$index}",
        'harga_paket' => 100000,
        'durasi' => '1 Hari',
    ]));

    foreach ($pakets as $index => $paket) {
        createDashboardOrder(
            $paket,
            "INV-PACKAGE-{$index}",
            'selesai',
            ($index + 1) * 100000,
            Carbon::create(2026, $index + 1, 10),
        );
    }

    createDashboardOrder(null, 'INV-CUSTOM', 'selesai', 900000, Carbon::create(2026, 6, 15));
    createDashboardOrder($pakets->first(), 'INV-PENDING', 'pending', 5000000, Carbon::create(2026, 6, 20));
    createDashboardOrder($pakets->first(), 'INV-OLD', 'selesai', 7000000, Carbon::create(2025, 12, 20));

    $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

    $response
        ->assertOk()
        ->assertViewHas('revenue', 10000000)
        ->assertViewHas('chartRevenu', fn ($revenue) => count($revenue) === 12
            && $revenue[0] === 100000
            && $revenue[5] === 1500000)
        ->assertViewHas('chartPaketLabel', fn ($labels) => $labels === [
            'Paket 1',
            'Paket 6',
            'Paket 5',
            'Paket 4',
            'Paket 3',
        ])
        ->assertViewHas('chartPaketData', fn ($data) => $data === [
            7100000,
            600000,
            500000,
            400000,
            300000,
        ]);
});
