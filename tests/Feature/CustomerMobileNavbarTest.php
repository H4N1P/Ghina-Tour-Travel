<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the guest account action inside the accessible mobile navigation', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="mobileNavToggle"', false)
        ->assertSee('aria-controls="mobileNavMenu"', false)
        ->assertSee('id="mobileNavMenu"', false)
        ->assertSee(route('login'))
        ->assertSee('Login');
});

it('shows dashboard and logout actions inside the authenticated mobile navigation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertSee(route('admin.dashboard'))
        ->assertSee(route('logout'))
        ->assertSee('Dashboard')
        ->assertSee('Logout');
});

it('provides the mobile navigation interaction helper', function () {
    $script = file_get_contents(resource_path('js/public-navbar.js'));

    expect($script)
        ->toContain('aria-expanded')
        ->toContain("event.key === 'Escape'")
        ->toContain('!navbar.contains(event.target)')
        ->toContain('window.innerWidth >= 1024');
});
