<?php

use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->paket = Paket::create([
        'nama_paket' => 'Paket Invoice Test',
        'harga_paket' => 250000,
        'durasi' => '3 Hari 2 Malam',
    ]);
});

function invoiceOrderPayload(array $overrides = []): array
{
    return array_merge([
        'nama_pemesan' => 'Pemesan Invoice',
        'no_hp' => '081234567890',
        'diskon' => 10,
        'total_harga' => 675000,
        'tanggal_acara' => '2026-07-10',
        'tanggal_selesai' => '2026-07-12',
        'jumlah_orang' => 3,
        'status' => 'pending',
    ], $overrides);
}

it('shows the package duration and event date range on a package invoice', function () {
    $pesanan = Pesanan::create([
        ...invoiceOrderPayload(),
        'id_paket' => $this->paket->id,
        'invoice' => 'INV-PACKAGE-INVOICE',
    ]);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.show', $pesanan))
        ->assertOk()
        ->assertSee('Paket Invoice Test')
        ->assertSee('Tanggal Acara')
        ->assertSee('10 July 2026 - 12 July 2026')
        ->assertSee('Durasi')
        ->assertSee('3 Hari 2 Malam');
});

it('uses the unified package invoice design for custom orders', function () {
    $pesanan = Pesanan::create([
        ...invoiceOrderPayload(),
        'invoice' => 'INV-CUSTOM-INVOICE',
        'is_custom' => true,
        'custom_places' => ['Dieng', 'Wonosobo'],
        'custom_fasilitas' => [
            ['nama_fasilitas' => 'Hotel', 'tipe_fasilitas' => 'akomodasi'],
            ['nama_fasilitas' => 'Bus Pariwisata', 'tipe_fasilitas' => 'transportasi'],
        ],
    ]);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.show', $pesanan))
        ->assertOk()
        ->assertSee('Paket Custom Travel')
        ->assertSee('10 July 2026 - 12 July 2026')
        ->assertSee('Dieng, Wonosobo')
        ->assertSee('Hotel (akomodasi), Bus Pariwisata (transportasi)')
        ->assertDontSee('invoice-sheet--custom', false)
        ->assertDontSee('Durasi');
});

it('shows a single date for one-day and legacy orders', function (?string $tanggalSelesai) {
    $pesanan = Pesanan::create([
        ...invoiceOrderPayload([
            'tanggal_selesai' => $tanggalSelesai,
        ]),
        'id_paket' => $this->paket->id,
        'invoice' => 'INV-SINGLE-DATE-' . ($tanggalSelesai ? 'CURRENT' : 'LEGACY'),
    ]);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.show', $pesanan))
        ->assertOk()
        ->assertSee('10 July 2026')
        ->assertDontSee('10 July 2026 - 10 July 2026');
})->with(['2026-07-10', null]);
