<?php

use App\Models\Peak;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('searches by peak name using live search', function () {
    Peak::factory()->create(['name' => 'Helvellyn', 'category' => 'Wainwrights']);
    Peak::factory()->create(['name' => 'Scafell Pike', 'category' => 'Wainwrights']);

    $this->actingAs($this->user)
        ->get(route('peaks.index', ['q' => 'Helv']))
        ->assertSee('Helvellyn')
        ->assertDontSee('Scafell Pike');
});

it('filters by category', function () {
    Peak::factory()->create(['name' => 'Helvellyn', 'category' => 'Wainwrights']);
    Peak::factory()->create(['name' => 'Ben Nevis', 'category' => 'Munros']);

    $this->actingAs($this->user)
        ->get(route('peaks.index', ['category' => 'Wainwrights']))
        ->assertSee('Helvellyn')
        ->assertDontSee('Ben Nevis');
});

it('combines search and category filters', function () {
    Peak::factory()->create(['name' => 'Helvellyn', 'category' => 'Wainwrights']);
    Peak::factory()->create(['name' => 'High Helvellyn', 'category' => 'Munros']);

    $this->actingAs($this->user)
        ->get(route('peaks.index', ['q' => 'Helvellyn', 'category' => 'Wainwrights']))
        ->assertSee('Helvellyn')
        ->assertDontSee('High Helvellyn');
});
