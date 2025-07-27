<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome obrigatório',
            'name.string' => 'Nome deve ser uma string',
            'name.max' => 'Nome não pode ter mais de 255 caracteres',
            'email.required' => 'Email obrigatório',
            'email.string' => 'Email deve ser uma string',
            'email.email' => 'Email deve ser um endereço de email válido',
            'email.max' => 'Email não pode ter mais de 255 caracteres',
            'email.unique' => 'Email já está em uso',
            'password.required' => 'Senha obrigatória',
            'password.string' => 'Senha deve ser uma string',
            'password.min' => 'Senha deve ter pelo menos 8 caracteres',
            'password.confirmed' => 'Confirmação de senha não corresponde',
        ];
    }
}
