<?php

use App\Models\User;

describe ('User tests', function(){
    test('login', function(){
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@testeds',
            'password' => bcrypt('password'),
        ]);
        $response = $this->post('/api/v1/auth/login', [
            'email' => 'test@testeds',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Login realizado com sucesso',
            ]);
    });

    test('login with invalid credentials', function(){
        User::factory()->create([
            'email' => 'test@testeds',
            'password' => bcrypt('password'),
        ]);
        $response = $this->post('/api/v1/auth/login', [
            'email' => 'test@testeds',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Credenciais inválidas.',
            ]);
    });
    test('login with missing fields', function () {
        $response = $this->post('/api/v1/auth/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    });

    test('login with non-existent user', function () {
        $response = $this->post('/api/v1/auth/login', [
            'email' => 'nonexistent@testeds',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Credenciais inválidas.',
            ]);
    });
    test('login with invalid email format', function () {
        $response = $this->post('/api/v1/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

} );
