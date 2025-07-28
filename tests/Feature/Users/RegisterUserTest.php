<?php

use App\Models\User;

describe ('User tests', function(){
    test('register', function () {
        $response = $this->post('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@testeds',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Usuário registrado com sucesso',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@testeds',
        ]);
    });


    test('register with existing email', function () {
        User::factory()->create([
            'email' => 'existing@testeds',
        ]);

        $response = $this->post('/api/v1/auth/register', [
            'name' => 'New User',
            'email' => 'existing@testeds',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    test('register with weak password', function () {
        $response = $this->post('/api/v1/auth/register', [
            'name' => 'Weak Password User',
            'email' => 'weak@testeds',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });

    test('register with missing fields', function () {
        $response = $this->post('/api/v1/auth/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    });
});
