<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_pomodoro' => 'required|integer',
            'pomodoro_value' => 'required|integer',
            'completed_pomodoro' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'total_pomodoro.required' => 'The total pomodoro field is required.',
            'total_pomodoro.integer' => 'The total pomodoro must be an integer.',
            'pomodoro_value.required' => 'The pomodoro value field is required.',
            'pomodoro_value.integer' => 'The pomodoro value must be an integer.',
            'completed_pomodoro.integer' => 'The completed pomodoro must be an integer.',
            'completed_pomodoro.nullable' => 'The completed pomodoro field is optional and can be null.',
        ];
    }
}
