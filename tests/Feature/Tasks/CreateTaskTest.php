<?php

use App\Models\Task;
use App\Models\User;

describe('Create Task tests', function () {
    test('Create task working', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('tasks.store'), [
            "title" => "Test Task",
            "description" => "This is a test task description.",
            "totalPomodori" => 48,
            "pomodoroValue" => 60,
            "taskDate" => "2025-08-17T22:47:13.121Z",
            "dueDate" => "2025-08-17T22:47:13.121Z"
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'user_id' => $user->id,
        ]);
    });

    test('Create task without authentication', function () {
        $response = $this->post(route('tasks.store'), [
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            "totalPomodori" => 10,
            "pomodoroValue" => 25,
            "taskDate" => "2025-08-17T22:47:13.121Z",
            "dueDate" => "2025-08-17T22:47:13.121Z"
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
        ])->post(route('tasks.store'), [
            "description" => "This is a test task description.",
            "totalPomodori" => 10,
            "pomodoroValue" => 25,
            "taskDate" => "2025-08-17T22:47:13.121Z",
            "dueDate" => "2025-08-17T22:47:13.121Z"
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    });

    test('Create task with missing description', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('tasks.store'), [
            "title" => "Test Task",
            "totalPomodori" => 2,
            "pomodoroValue" => 25,
            "taskDate" => "2025-08-17T22:47:13.121Z",
            "dueDate" => "2025-08-17T22:47:13.121Z"
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
    });
});
