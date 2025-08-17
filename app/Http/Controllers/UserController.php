<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Exception;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json([
                "status" => true,
                "message" => "Usuário registrado com sucesso",
                "data" => $user->only(['id', 'name', 'email'])
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Falha ao registrar o usuário",
                "error" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'Logout successful'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }
    }
}
