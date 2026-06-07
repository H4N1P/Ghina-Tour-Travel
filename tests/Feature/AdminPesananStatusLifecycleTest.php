<?php

use App\Models\Paket;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->paket = Paket::create([
        'nama_paket' => 'Paket Status Test',
        'harga_paket' => 250000,
        'durasi' => '3 Hari 2 Malam',
    ]);
});

function statusOrderPayload(Paket $paket, array $overrides = []): array
{
    return array_merge([
        'id_paket' => $paket->id,
        'nama_pemesan' => 'Pemesan Status',
        'no_hp' => '081234567890',
        'diskon' => 0,
        'total_harga' => 750000,
        'tanggal_acara' => '2026-07-10',
        'tanggal_selesai' => '2026-07-12',
        'jumlah_orang' => 3,
        'status' => 'pending',
    ], $overrides);
}

it('shows pending as read only initial status on both create forms', function () {
    $this->actingAs($this->user)
        ->get(route('admin.pesanan.create'))
        ->assertOk()
        ->assertSee('Status awal pesanan')
        ->assertDontSee('name="status"', false);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.create-custom'))
        ->assertOk()
        ->assertSee('Status awal pesanan')
        ->assertDontSee('name="status"', false);
});

it('always stores new package and custom orders as pending', function () {
    $this->actingAs($this->user)->post(
        route('admin.pesanan.store'),
        statusOrderPayload($this->paket, ['status' => 'selesai']),
    )->assertRedirect(route('admin.pesanan.index'));

    $this->actingAs($this->user)->post(route('admin.pesanan.store-custom'), [
        ...statusOrderPayload($this->paket, [
            'id_paket' => null,
            'nama_pemesan' => 'Pemesan Custom Status',
            'status' => 'batal',
        ]),
        'custom_places' => ['Dieng'],
    ])->assertRedirect(route('admin.pesanan.index'));

    expect(Pesanan::where('nama_pemesan', 'Pemesan Status')->value('status'))->toBe('pending')
        ->and(Pesanan::where('nama_pemesan', 'Pemesan Custom Status')->value('status'))->toBe('pending');
});

it('allows pending package and custom orders to become final', function () {
    $packageOrder = Pesanan::create([
        ...statusOrderPayload($this->paket),
        'invoice' => 'INV-PENDING-PACKAGE',
    ]);
    $customOrder = Pesanan::create([
        ...statusOrderPayload($this->paket, [
            'id_paket' => null,
            'nama_pemesan' => 'Pemesan Custom Pending',
        ]),
        'invoice' => 'INV-PENDING-CUSTOM',
        'is_custom' => true,
        'custom_places' => ['Bali'],
    ]);

    $this->actingAs($this->user)
        ->put(route('admin.pesanan.update', $packageOrder), statusOrderPayload($this->paket, [
            'status' => 'selesai',
        ]))
        ->assertRedirect(route('admin.pesanan.index'));

    $this->actingAs($this->user)
        ->put(route('admin.pesanan.update', $customOrder), statusOrderPayload($this->paket, [
            'id_paket' => null,
            'status' => 'batal',
        ]))
        ->assertRedirect(route('admin.pesanan.index'));

    expect($packageOrder->refresh()->status)->toBe('selesai')
        ->and($customOrder->refresh()->status)->toBe('batal');
});

it('blocks edit pages and update requests for final orders while keeping details accessible', function (string $status) {
    $pesanan = Pesanan::create([
        ...statusOrderPayload($this->paket, ['status' => $status]),
        'invoice' => "INV-FINAL-{$status}",
    ]);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.edit', $pesanan))
        ->assertRedirect(route('admin.pesanan.show', $pesanan))
        ->assertSessionHas('failed');

    $this->actingAs($this->user)
        ->put(route('admin.pesanan.update', $pesanan), statusOrderPayload($this->paket, [
            'nama_pemesan' => 'Nama Yang Tidak Boleh Tersimpan',
            'status' => 'pending',
        ]))
        ->assertRedirect(route('admin.pesanan.show', $pesanan))
        ->assertSessionHas('failed');

    expect($pesanan->refresh()->nama_pemesan)->toBe('Pemesan Status')
        ->and($pesanan->status)->toBe($status);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.show', $pesanan))
        ->assertOk()
        ->assertSee('Status final, pesanan tidak dapat diubah')
        ->assertSee('Print Invoice')
        ->assertDontSee(route('admin.pesanan.edit', $pesanan));
})->with(['selesai', 'batal']);

it('shows informational edit actions without edit links for final orders in the order list', function () {
    $selesai = Pesanan::create([
        ...statusOrderPayload($this->paket, ['status' => 'selesai']),
        'invoice' => 'INV-LIST-SELESAI',
    ]);
    $batal = Pesanan::create([
        ...statusOrderPayload($this->paket, ['status' => 'batal']),
        'invoice' => 'INV-LIST-BATAL',
    ]);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.index'))
        ->assertOk()
        ->assertDontSee(route('admin.pesanan.edit', $selesai))
        ->assertDontSee(route('admin.pesanan.edit', $batal))
        ->assertSee('data-admin-notice', false)
        ->assertSee('Pesanan berstatus Selesai sudah final dan tidak dapat diubah.')
        ->assertSee('Pesanan berstatus Batal sudah final dan tidak dapat diubah.');
});

it('keeps a real edit link for pending orders in the order list', function () {
    $pending = Pesanan::create([
        ...statusOrderPayload($this->paket),
        'invoice' => 'INV-LIST-PENDING',
    ]);

    $this->actingAs($this->user)
        ->get(route('admin.pesanan.index'))
        ->assertOk()
        ->assertSee(route('admin.pesanan.edit', $pending));
});
