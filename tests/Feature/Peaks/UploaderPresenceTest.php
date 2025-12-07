<?php

use App\Models\Peak;
use App\Models\User;

it('shows the uploader on the peak show page', function () {
    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $this->actingAs($user)
        ->get(route('peaks.show', $peak))
        ->assertSee('id="uploader-input"', false)
        ->assertStatus(200);
});

