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
            'success' => true,
            'data' => $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'totalPomodori' => $task->totalPomodori,
                    'pomodoroValue' => $task->pomodoroValue,
                    'completedPomodori' => $task->completedPomodori,
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
                'success' => true,
                'message' => 'Task created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage(),
            ], 500);
        }


    }

    public function show(Request $request, $id){
        $task = Task::find($id);

        if(!$task){
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
                'data' => null,
                'errors' => ['Task with the given ID does not exist.'],
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task found',
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'totalPomodori' => $task->totalPomodori,
                'pomodoroValue' => $task->pomodoroValue,
                'completedPomodori' => $task->completedPomodori,
                'status' => $task->status,
                'taskDate' => $task->taskDate,
                'dueDate' => $task->dueDate,
                'assignedAt' => $task->assigned_at,
                'completedAt' => $task->completed_at,
            ],
            'errors' => [],
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request, $id){

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ], Response::HTTP_OK);
    }

    public function update(PutTaskRequest $request, Task $task){

        $validatedData = $request->validated();


        // Verifica se pertence ao usuário
        if ($task->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }


        $task->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'totalPomodori' => $task->total_pomodori,
                'pomodoroValue' => $task->pomodoroValue,
                'completedPomodori' => $task->completed_pomodori,
                'status' => $task->status,
                'taskdate' => $task->taskdate,
                'dueDate' => $task->dueDate,
                'completedAt' => $task->completedPomodori,
            ],
            'errrors' => $task->getErrors(),
        ], Response::HTTP_OK);
    }

}
