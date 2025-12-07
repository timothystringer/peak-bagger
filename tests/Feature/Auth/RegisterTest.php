<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('allows a user to register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'email' => 'newuser@example.com',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    expect(Hash::check('password', $user->password))->toBeTrue();
});
