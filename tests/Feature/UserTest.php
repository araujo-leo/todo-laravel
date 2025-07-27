<?php

use App\Models\User;

describe ('User tests', function(){
    test('register', function () {
        $response = $this->post('/api/v1/register', [
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

    test('login', function(){
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@testeds',
            'password' => bcrypt('password'),
        ]);
        $response = $this->post('/api/v1/login', [
            'email' => 'test@testeds',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Login successful',
            ]);
    });

    test('logout', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout successful',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    });
});
