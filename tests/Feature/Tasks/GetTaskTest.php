<?php

use App\Models\Task;
use App\Models\User;

describe('Get Task tests', function () {

    test('Get Task successfully', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'totalPomodori' => $task->totalPomodori,
                    'pomodoroValue' => $task->pomodoroValue,
                    'completedPomodori' => $task->completedPomodori,
                    'status' => $task->status,

                ],
            ]);
    });

    test('Get Task Unauthorized', function () {
        $task = Task::factory()->create();

        $response = $this->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

    test('Get Task Not Found', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks/999999'); // ID que não existe

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
            ]);
    });

    test('Get Task with invalid token', function () {
        $task = Task::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer inactive_token',
        ])->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });
});
