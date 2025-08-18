<?php
use App\Models\Task;
use App\Models\User;

describe('PatchTaskTest', function () {

    test('atualizar task com sucesso somente descrição', function () {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patch('/api/v1/tasks/' . $task->id, [
            'description' => 'Descrição atualizada via PATCH',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'description' => 'Descrição atualizada via PATCH',
        ]);

        $task->refresh();
        $this->assertEquals('Descrição atualizada via PATCH', $task->description);
    });

    test('tentar atualizar task sem autenticação', function () {
        $task = Task::factory()->create();

        $response = $this->patch('/api/v1/tasks/' . $task->id, [
            'title' => 'Nova Tarefa',
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
        ])->patch('/api/v1/tasks/9999', [
            'title' => 'Tarefa Inexistente',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Task not found',
            ]);
    });

    test('tentar atualizar task sem campos válidos', function () {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patch('/api/v1/tasks/' . $task->id, [
            'invalidField' => 'Não deve atualizar',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'No valid fields provided for update',
            ]);
    });

});
