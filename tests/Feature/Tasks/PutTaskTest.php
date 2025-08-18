<?php
use App\Models\Task;
use App\Models\User;

describe('atualizar task', function () {
    test('atualizar task com sucesso', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/tasks', [
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

        $task = Task::where('title', 'Test Task')->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/v1/tasks/'. $task['id'], [
            "title" => "Test Task updated",
            "description" => "This is a test task description updated.",
            "totalPomodori" => 48,
            "pomodoroValue" => 60,
            "taskDate" => "2025-08-17T22:47:13.121Z",
            "dueDate" => "2025-08-17T22:47:13.121Z"
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task['id'],
            'title' => 'Test Task updated',
            'description' => 'This is a test task description updated.',
            'totalPomodori' => 48,
            'pomodoroValue' => 60,
        ]);


    });

    test('tentar atualizar task sem autenticação', function () {
        $task = Task::factory()->create();

        $response = $this->put('/api/v1/tasks/' . $task->id, [
            'title' => 'Tarefa Atualizada',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });

    test('tentar atualizar task inexistente', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/v1/tasks/9999', [
            'title' => 'Tarefa Inexistente',
            'description' => 'Esta tarefa não existe.',
            'totalPomodori' => 5,
            'pomodoroValue' => 25,
            'taskDate' => '2025-08-17T22:47:13.121Z',
            'dueDate' => '2025-08-17T22:47:13.121Z'
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
                'errors' => 'Task with the given ID does not exist.',
            ]);
    });

    test('tentar atualizar task com dados inválidos', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/v1/tasks/' . $task->id, [
            'title' => '',
            'totalPomodori' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    });
});
