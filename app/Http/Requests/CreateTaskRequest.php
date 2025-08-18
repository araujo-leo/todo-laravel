<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'totalPomodori' => 'required|integer|min:1',
            'pomodoroValue' => 'required|integer|min:1',
            'completedPomodori' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,in_progress,completed,cancelled',
            'taskDate' => 'nullable|date',
            'dueDate' => 'nullable|date',
            'assignedAt' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser uma string.',
            'title.max' => 'O título pode ter no máximo 255 caracteres.',
            'totalPomodori.required' => 'O campo totalPomodori é obrigatório.',
            'totalPomodori.integer' => 'O campo totalPomodori deve ser um número inteiro.',
            'pomodoroValue.required' => 'O campo pomodoroValue é obrigatório.',
            'pomodoroValue.integer' => 'O campo pomodoroValue deve ser um número inteiro.',
        ];
    }
}
