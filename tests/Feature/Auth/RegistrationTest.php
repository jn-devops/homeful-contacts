<?php

use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'mobile' => '09171234567',
        'password' => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => '1999-03-17',
        'gross_monthly_income' => 15000
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
