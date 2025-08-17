<?php
use App\Models\Task;
use App\Models\User;

describe('List Tasks tests', function () {

    test('List tasks successfully', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'totalPomodori' => 2,
            'pomodoroValue' => 25,
            'completedPomodori' => 0,
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'totalPomodori',
                        'pomodoroValue',
                        'completedPomodori',
                    ],
                ],
            ]);
    });

    test('List tasks without authentication', function () {
        $response = $this->get('/api/v1/tasks');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

    test('List tasks with no tasks available', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
            ]);
    });

    test('List tasks with invalid token', function () {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->get('/api/v1/tasks');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

});
