<?php

use App\Models\Task;
use App\Models\User;

describe('Create Task tests', function () {
    test('Create task working', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/auth/logout');


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'total_pomodoro' => 2,
            'pomodoro_value' => 25,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Task created successfully',
            ]);


        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
        ]);


    });

    test('Create task without authentication', function () {
        $response = $this->post('/api/v1/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

    test('Create task with missing title', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/tasks', [
            'description' => 'This is a test task description.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    });

    test('Create task with missing description', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/tasks', [
            'title' => 'Test Task',
            'total_pomodoro' => 2,
            'pomodoro_value' => 25,
        ]);

        $response->assertStatus(201)
        ->assertJson([
            'status' => true,
            'message' => 'Task created successfully',
        ]);


        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
        ]);
    });

});
