<?php

use App\Models\User;

it('allows navigation from dashboard to peaks index (server-side check)', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $dashboard = $this->get('/dashboard');
    $dashboard->assertStatus(200);
    $dashboard->assertSee('Peaks');

    $peaks = $this->get('/peaks');
    $peaks->assertStatus(200);
    $peaks->assertSee('Peaks');
});

