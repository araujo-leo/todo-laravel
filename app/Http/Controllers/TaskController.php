<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
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

        try{
            $task = Task::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? null,
                'user_id' => auth()->id(),
                'total_pomodoro' => $validatedData['total_pomodoro'],
                'pomodoro_value' => $validatedData['pomodoro_value'],
                'completed_pomodoro' => 0,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Task created successfully',
            ],201);
        }catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage(),
            ], 500);
        }


    }
}
