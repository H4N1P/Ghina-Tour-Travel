<?php

use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->paket = Paket::create([
        'nama_paket' => 'Paket Test',
        'harga_paket' => 250000,
        'durasi' => '3 Hari 2 Malam',
    ]);
});

function packageOrderPayload(Paket $paket, array $overrides = []): array
{
    return array_merge([
        'id_paket' => $paket->id,
        'nama_pemesan' => 'Pemesan Test',
        'no_hp' => '081234567890',
        'diskon' => 10,
        'total_harga' => 675000,
        'tanggal_acara' => '2026-07-10',
        'tanggal_selesai' => '2026-07-12',
        'jumlah_orang' => 3,
        'status' => 'pending',
    ], $overrides);
}

it('stores a package order with an inclusive event date range', function () {
    $response = $this->actingAs($this->user)
        ->post(route('admin.pesanan.store'), packageOrderPayload($this->paket));

    $response->assertRedirect(route('admin.pesanan.index'));

    $pesanan = Pesanan::where('id_paket', $this->paket->id)->firstOrFail();

    expect($pesanan->tanggal_acara->toDateString())->toBe('2026-07-10')
        ->and($pesanan->tanggal_selesai->toDateString())->toBe('2026-07-12');
});

it('stores a custom order with an inclusive event date range', function () {
    $response = $this->actingAs($this->user)->post(route('admin.pesanan.store-custom'), [
        'nama_pemesan' => 'Pemesan Custom',
        'no_hp' => '081234567891',
        'diskon' => 0,
        'total_harga' => 900000,
        'tanggal_acara' => '2026-08-01',
        'tanggal_selesai' => '2026-08-04',
        'jumlah_orang' => 4,
        'custom_places' => ['Dieng', 'Wonosobo'],
    ]);

    $response->assertRedirect(route('admin.pesanan.index'));

    $pesanan = Pesanan::where('nama_pemesan', 'Pemesan Custom')->firstOrFail();

    expect($pesanan->is_custom)->toBeTruthy()
        ->and($pesanan->tanggal_acara->toDateString())->toBe('2026-08-01')
        ->and($pesanan->tanggal_selesai->toDateString())->toBe('2026-08-04');
});

it('updates package and custom order date ranges', function () {
    $packageOrder = Pesanan::create([
        ...packageOrderPayload($this->paket),
        'invoice' => 'INV-PACKAGE-TEST',
        'is_custom' => false,
    ]);
    $customOrder = Pesanan::create([
        ...packageOrderPayload($this->paket, [
            'id_paket' => null,
            'nama_pemesan' => 'Pemesan Custom',
        ]),
        'invoice' => 'INV-CUSTOM-TEST',
        'is_custom' => true,
        'custom_places' => ['Bali'],
    ]);

    $this->actingAs($this->user)
        ->put(route('admin.pesanan.update', $packageOrder), packageOrderPayload($this->paket, [
            'tanggal_acara' => '2026-09-05',
            'tanggal_selesai' => '2026-09-08',
        ]))
        ->assertRedirect(route('admin.pesanan.index'));

    $this->actingAs($this->user)
        ->put(route('admin.pesanan.update', $customOrder), packageOrderPayload($this->paket, [
            'id_paket' => null,
            'tanggal_acara' => '2026-10-02',
            'tanggal_selesai' => '2026-10-06',
        ]))
        ->assertRedirect(route('admin.pesanan.index'));

    expect($packageOrder->refresh()->tanggal_selesai->toDateString())->toBe('2026-09-08')
        ->and($customOrder->refresh()->tanggal_selesai->toDateString())->toBe('2026-10-06');
});

it('rejects an end date before the start date', function () {
    $response = $this->actingAs($this->user)
        ->from(route('admin.pesanan.create'))
        ->post(route('admin.pesanan.store'), packageOrderPayload($this->paket, [
            'tanggal_acara' => '2026-07-10',
            'tanggal_selesai' => '2026-07-09',
        ]));

    $response
        ->assertRedirect(route('admin.pesanan.create'))
        ->assertSessionHasErrors('tanggal_selesai');

    $this->assertDatabaseCount('pesanans', 0);
});

it('filters orders when the selected date is inside their event range', function () {
    Pesanan::create([
        ...packageOrderPayload($this->paket),
        'invoice' => 'INV-IN-RANGE',
    ]);
    Pesanan::create([
        ...packageOrderPayload($this->paket, [
            'tanggal_acara' => '2026-07-20',
            'tanggal_selesai' => '2026-07-21',
        ]),
        'invoice' => 'INV-OUTSIDE-RANGE',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.pesanan.index', ['tanggal' => '2026-07-11']));

    $response
        ->assertOk()
        ->assertSee('INV-IN-RANGE')
        ->assertDontSee('INV-OUTSIDE-RANGE');
});
