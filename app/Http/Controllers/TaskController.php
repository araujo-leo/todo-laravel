<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\PutTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends Controller
{
    public function index(Request $request){
        $tasks = Task::where('user_id', auth()->id())->get();

        return response()->json([
            'status' => true,
            'data' => $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'total_pomodoro' => $task->total_pomodoro,
                    'pomodoro_value' => $task->pomodoro_value,
                    'completed_pomodoro' => $task->completed_pomodoro,
                ];
            }),
        ], RESPONSE::HTTP_OK);
    }


    public function create(CreateTaskRequest $request){
        $validatedData = $request->validated();

        try {
            $task = Task::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? null,
                'user_id' => auth()->id(),
                'totalPomodori' => $validatedData['totalPomodori'],
                'pomodoroValue' => $validatedData['pomodoroValue'],
                'completedPomodori' => $validatedData['completedPomodori'] ?? 0,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Task created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage(),
            ], 500);
        }


    }

    public function show(Request $request, Task $task){
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'total_pomodoro' => $task->total_pomodoro,
                'pomodoro_value' => $task->pomodoro_value,
                'completed_pomodoro' => $task->completed_pomodoro,
            ],
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request, Task $task){
        // Verifica se pertence ao usuário
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Task deleted successfully',
        ], Response::HTTP_OK);
    }

    public function update(PutTaskRequest $request, Task $task){

        $validatedData = $request->validated();


        // Verifica se pertence ao usuário
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }


        $task->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Task updated successfully',
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'totalPomodori' => $task->total_pomodori,
                'pomodoroValue' => $task->pomodoro_value,
                'completedPomodori' => $task->completed_pomodori,
                'status' => $task->status,
                'taskdate' => $task->taskdate,
                'dueDate' => $task->dueDate,
                'completedAt' => $task->completed_pomodoro,
            ],
            'errrors' => $task->getErrors(),
        ], Response::HTTP_OK);
    }

}
