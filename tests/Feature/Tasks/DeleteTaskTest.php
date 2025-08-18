<?php

use App\Models\User;
use App\Models\Task;
use function Pest\Laravel\{delete, assertSoftDeleted};

describe("Delete Task tests", function () {
    test("Soft Delete Task", function () {
        $user = User::factory()->create();
        $token = $user->createToken("TestToken")->plainTextToken;

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete(route('tasks.destroy', $task->id) );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    });

    test("Delete Task Unauthorized", function () {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task->id) );

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

    test("Delete Task Not Found", function () {
        $user = User::factory()->create();
        $token = $user->createToken("TestToken")->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/api/v1/tasks/9999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found',
            ]);
    });

    test("Delete Task with invalid token", function () {
        $task = Task::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer inactive_token',
        ])->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

    test("Delete Task twice", function () {
        $user = User::factory()->create();
        $token = $user->createToken("TestToken")->plainTextToken;

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete(route('tasks.destroy', $task->id));

        $response1->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete(route('tasks.destroy', $task->id));

        $response2->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
            ]);
    });
});
