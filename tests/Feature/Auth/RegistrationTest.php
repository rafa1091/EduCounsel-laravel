<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',

        'nim' => '3312401001',
        'no_hp' => '081234567890',
        'role' => 'mahasiswa',
    ]);

    $this->assertAuthenticated();

    $response->assertRedirect(route('dashboard', absolute: false));
});
