<?php

use App\Models\User;

it('shows peaks nav link on dashboard for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertStatus(200)
        ->assertSee('Peaks');
});

