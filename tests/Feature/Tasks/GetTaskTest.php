<?php

use App\Models\Task;
use App\Models\User;

describe('Create Task tests', function () {
    test('Get Task', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $task = Task::factory()->create(['user_id' => $user->id]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'total_pomodoro' => $task->total_pomodoro,
                    'pomodoro_value' => $task->pomodoro_value,
                    'completed_pomodoro' => $task->completed_pomodoro,
                ],
            ]);

    });

    test('Get Task Unauthorized', function () {
        $task = Task::factory()->create();
        $response = $this->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(401);
    });

    test('Get Task Not Found', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks/9999');

        $response->assertStatus(404);
    });

    test('Get Task with invalid token', function () {
        $task = Task::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . 'inactive_token',
        ])->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);

    });
});

