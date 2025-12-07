<?php

use App\Models\User;

it('navigates from the dashboard to the peaks index via the Peaks link', function () {
    $user = User::factory()->create();

    // authenticate and visit dashboard
    $this->actingAs($user);

    $page = visit('/dashboard');

    $page->assertSee('Dashboard');
    $page->assertSee('Peaks');

    // Click the Peaks link and assert we land on the peaks index
    $page->click('Peaks');

    $page->assertSee('Peaks');
    $page->assertPathIs('/peaks');
});

