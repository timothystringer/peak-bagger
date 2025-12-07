<?php

it('shows the register page with the create account button', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);

    $response->assertSee('Create an account');
    $response->assertSee('data-test="register-user-button"', false);
});

