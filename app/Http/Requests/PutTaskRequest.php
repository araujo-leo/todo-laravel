<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'totalPomodori' => 'nullable|integer|min:1',
            'pomodoroValue' => 'nullable|integer|min:1',
            'completedPomodori' => 'nullable|integer|min:0',
            'taskdate' => 'nullable|date',
            'dueDate' => 'nullable|date',
            'status' => 'nullable|integer|in:0,1,2'
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'O título deve ser uma string.',
            'title.max' => 'O título pode ter no máximo 255 caracteres.',
            'description.string' => 'A descrição deve ser uma string.',
            'description.max' => 'A descrição pode ter no máximo 1000 caracteres.',
            'totalPomodori.integer' => 'O campo totalPomodori deve ser um número inteiro.',
            'pomodoroValue.integer' => 'O campo pomodoroValue deve ser um número inteiro.',
            'completedPomodori.integer' => 'O campo completedPomodori deve ser um número inteiro.',
            'taskdate.date' => 'A data da tarefa deve ser uma data válida.',
            'dueDate.date' => 'A data de vencimento deve ser uma data válida.',
            'status.in' => 'O status deve ser 0 (pendente), 1 (em progresso) ou 2 (concluído).',
        ];
    }
}
