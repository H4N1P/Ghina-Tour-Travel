<?php

use App\Models\Paket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses the shared draggable carousel on the public package slider', function () {
    Paket::create([
        'nama_paket' => 'Paket Drag',
        'harga_paket' => 250000,
        'durasi' => '1 Hari',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-public-carousel', false)
        ->assertSee('data-carousel-item=".package-card"', false)
        ->assertSee('data-carousel-track', false)
        ->assertSee('data-carousel-prev', false)
        ->assertSee('data-carousel-next', false)
        ->assertSee('public-carousel__btn paket-slider__btn--prev', false)
        ->assertSee('public-carousel__btn paket-slider__btn--next', false)
        ->assertDontSee('paketTrack', false)
        ->assertDontSee('scrollAmount()', false);
});

it('provides pointer drag and click suppression in the shared carousel helper', function () {
    $script = file_get_contents(resource_path('js/public-carousel.js'));

    expect($script)
        ->toContain("track.addEventListener('pointerdown'")
        ->toContain("track.addEventListener('pointermove'")
        ->toContain("track.addEventListener('pointerup'")
        ->toContain("track.setPointerCapture?.(event.pointerId);\n            track.classList.add('is-dragging')")
        ->not->toContain("isHorizontalDrag = false;\n        track.setPointerCapture?.(event.pointerId);")
        ->toContain('event.preventDefault()')
        ->toContain('event.stopPropagation()')
        ->toContain('previousButton.disabled')
        ->toContain('nextButton.disabled');
});
