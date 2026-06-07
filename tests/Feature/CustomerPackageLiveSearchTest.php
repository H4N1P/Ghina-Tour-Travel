<?php

use App\Models\Destinasi;
use App\Models\Paket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->baliPackage = Paket::create([
        'nama_paket' => 'Liburan Bali',
        'harga_paket' => 750000,
        'durasi' => '4 Hari 3 Malam',
    ]);
    $this->bandungPackage = Paket::create([
        'nama_paket' => 'Wisata Jawa Barat',
        'harga_paket' => 500000,
        'durasi' => '3 Hari 2 Malam',
    ]);

    Destinasi::create([
        'id_paket' => $this->bandungPackage->id,
        'nama_destinasi' => 'Kawah Putih Bandung',
    ]);
});

it('shows the standard live search field without the old search hero or search button', function () {
    $this->get(route('packages'))
        ->assertOk()
        ->assertSee('Semua Paket')
        ->assertSee('Cari nama paket atau destinasi...')
        ->assertSee('id="package-search-form"', false)
        ->assertDontSee('search-hero', false)
        ->assertDontSee('>Cari</button>', false);
});

it('filters packages by package name and destination name', function (string $query, string $visible, string $hidden) {
    $this->get(route('packages', ['q' => $query]))
        ->assertOk()
        ->assertSee($visible)
        ->assertDontSee($hidden);
})->with([
    ['Liburan', 'Liburan Bali', 'Wisata Jawa Barat'],
    ['Kawah Putih', 'Wisata Jawa Barat', 'Liburan Bali'],
]);

it('shows an empty state when no package matches', function () {
    $this->get(route('packages', ['q' => 'Tidak Ada Hasil']))
        ->assertOk()
        ->assertSee('Tidak Ada Paket Ditemukan')
        ->assertDontSee('Liburan Bali')
        ->assertDontSee('Wisata Jawa Barat');
});

it('returns only the result partial for ajax requests', function () {
    $this->get(route('packages', ['q' => 'Kawah']), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertSee('Wisata Jawa Barat')
        ->assertDontSee('Liburan Bali')
        ->assertDontSee('Semua Paket')
        ->assertDontSee('package-search-form', false)
        ->assertDontSee('<html', false);
});

it('keeps filtered pagination links in ajax results', function () {
    foreach (range(1, 13) as $index) {
        Paket::create([
            'nama_paket' => "Trip Pantai {$index}",
            'harga_paket' => 300000 + $index,
            'durasi' => '1 Hari',
        ]);
    }

    $this->get(route('packages', ['q' => 'Trip Pantai']), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertSee('ui-pagination', false)
        ->assertSee('q=Trip%20Pantai', false)
        ->assertSee('page=2', false);
});
