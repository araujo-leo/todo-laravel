<?php
use App\Models\Task;
use App\Models\User;

describe('List Tasks tests', function () {
    test('List tasks working', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'This is a test task description.',
            'total_pomodoro' => 2,
            'pomodoro_value' => 25,
            'completed_pomodoro' => 0,
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
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
                'status' => true,
                'data' => [],
            ]);
    });

    test('List tasks with invalid token', closure: function () {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->get('/api/v1/tasks');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });
});
