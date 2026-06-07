<?php

use App\Models\CompanyProfile;
use App\Models\Fasilitas;
use App\Models\Gallery;
use App\Models\Paket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the complete package image, facility carousel, capacity, and normalized whatsapp link', function () {
    CompanyProfile::create([
        'about' => 'Profil perjalanan',
        'vision_mission' => 'Melayani perjalanan terbaik',
        'whatsapp' => '+62 857-0773-3901',
        'email' => 'info@example.test',
        'address' => 'Purwokerto',
        'instagram' => '@ghinatourtravel',
    ]);

    $package = Paket::create([
        'nama_paket' => 'Paket Jogja',
        'image' => 'packages/jogja.jpg',
        'harga_paket' => 850000,
        'durasi' => '3 Hari 2 Malam',
        'note' => 'Harga untuk SEAT 50 PAX. Harga dapat berubah sesuai musim.',
    ]);

    $bus = Fasilitas::create([
        'id_paket' => $package->id,
        'tipe_fasilitas' => 'transportasi',
        'nama_fasilitas' => 'Bus AC Pariwisata',
        'image' => 'facilities/bus.jpg',
    ]);
    $hotel = Fasilitas::create([
        'id_paket' => $package->id,
        'tipe_fasilitas' => 'akomodasi',
        'nama_fasilitas' => 'Hotel Bintang 4',
    ]);

    Gallery::create([
        'id_fasilitas' => $bus->id,
        'path' => 'facilities/bus-interior.jpg',
        'keterangan' => 'Kursi nyaman dan lega',
        'type' => 'image',
    ]);
    Gallery::create([
        'id_fasilitas' => $bus->id,
        'path' => 'facilities/bus-video.mp4',
        'keterangan' => 'Video bus',
        'type' => 'video',
    ]);

    $this->get(route('package.detail', $package))
        ->assertOk()
        ->assertSee('detail-hero__blur', false)
        ->assertSee('object-contain', false)
        ->assertSee('facility-carousel', false)
        ->assertSee('facility-price-layout', false)
        ->assertSee('grid-template-columns: minmax(0, 1fr) 300px', false)
        ->assertSee('--facility-visible-slides: 3', false)
        ->assertSee('--facility-visible-slides: 2', false)
        ->assertSee('--facility-visible-slides: 1', false)
        ->assertSee('data-public-carousel', false)
        ->assertSee('data-carousel-track', false)
        ->assertSee('data-carousel-prev', false)
        ->assertSee('data-carousel-next', false)
        ->assertSee('Bus AC Pariwisata')
        ->assertSee('Kursi nyaman dan lega')
        ->assertSee('Hotel Bintang 4')
        ->assertSee('Foto fasilitas belum tersedia')
        ->assertSee('facilities/bus-interior.jpg', false)
        ->assertDontSee('facilities/bus-video.mp4', false)
        ->assertSee('Dokumentasi')
        ->assertSee('href="' . route('photos') . '"', false)
        ->assertSee('aria-label="Banner Custom"', false)
        ->assertSee('Rp 850.000', false)
        ->assertSee('/pax', false)
        ->assertSee('Maks. 50 pax/slot')
        ->assertDontSee('Harga untuk SEAT 50 PAX')
        ->assertSee('Harga dapat berubah sesuai musim.')
        ->assertSee('https://wa.me/6285707733901', false)
        ->assertSee('085707733901');
});

it('parses supported package capacity notes and removes only the ambiguous capacity phrase', function (
    string $note,
    ?int $capacity,
    ?string $displayNote,
) {
    $package = new Paket(['note' => $note]);

    expect($package->maxPaxFromNote())->toBe($capacity)
        ->and($package->displayNote())->toBe($displayNote);
})->with([
    ['Harga untuk 50 PAX', 50, null],
    ['Harga untuk SEAT 46 PAX. Harga dapat berubah.', 46, 'Harga dapat berubah.'],
    ['Harga untuk 100 siswa. Termasuk makan.', 100, 'Termasuk makan.'],
    ['Harga mengikuti musim.', null, 'Harga mengikuti musim.'],
]);

it('normalizes whatsapp numbers for display and wa links', function (string $number) {
    expect(CompanyProfile::whatsappDisplay($number))->toBe('085707733901')
        ->and(CompanyProfile::whatsappLinkNumber($number))->toBe('6285707733901');
})->with([
    '085707733901',
    '6285707733901',
    '+62 857-0773-3901',
]);

it('uses the official whatsapp fallback when no company profile exists', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('085707733901')
        ->assertSee('https://wa.me/6285707733901', false);
});
