<?php

use App\Models\Gallery;
use App\Models\Paket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses the shared gallery-style pagination on the all packages page', function () {
    foreach (range(1, 13) as $index) {
        Paket::create([
            'nama_paket' => "Paket {$index}",
            'harga_paket' => 100000 + $index,
            'durasi' => '1 Hari',
        ]);
    }

    $this->get(route('packages'))
        ->assertOk()
        ->assertSee('ui-pagination', false)
        ->assertSee('ui-pagination__btn', false)
        ->assertSee('ui-pagination__page', false);
});

it('uses the shared gallery-style pagination on the all photos page', function () {
    foreach (range(1, 13) as $index) {
        Gallery::create([
            'path' => "photos/photo-{$index}.jpg",
            'keterangan' => "Foto {$index}",
            'type' => 'image',
        ]);
    }

    $this->get(route('photos'))
        ->assertOk()
        ->assertSee('ui-pagination', false)
        ->assertSee('ui-pagination__btn', false)
        ->assertSee('ui-pagination__page', false)
        ->assertDontSee('pg-btn', false);
});
