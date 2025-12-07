<?php

use App\Http\Controllers\AscentController;
use App\Models\Ascent;
use App\Models\Peak;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

it('allows the owner to delete their ascent', function () {
    $user = User::factory()->create();
    $peak = Peak::factory()->create();

    $this->actingAs($user);

    $ascent = Ascent::create([
        'user_id' => $user->id,
        'peak_id' => $peak->id,
        'date' => now()->toDateString(),
        'notes' => 'test',
    ]);

    $controller = app(AscentController::class);
    $response = $controller->destroy($peak, $ascent);
    expect($response->getStatusCode())->toBe(204);

    expect(Ascent::find($ascent->id))->toBeNull();
});

it('prevents other users from deleting someone else\'s ascent', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $peak = Peak::factory()->create();

    $ascent = Ascent::create([
        'user_id' => $owner->id,
        'peak_id' => $peak->id,
        'date' => now()->toDateString(),
        'notes' => 'test',
    ]);

    $this->actingAs($other);

    $controller = app(AscentController::class);

    try {
        $controller->destroy($peak, $ascent);
        // If we reach here the authorization did not fire
        throw new \Exception('Expected AuthorizationException, none thrown');
    } catch (AuthorizationException $e) {
        // expected
        expect(true)->toBeTrue();
    }

    expect(Ascent::find($ascent->id))->not->toBeNull();
});
