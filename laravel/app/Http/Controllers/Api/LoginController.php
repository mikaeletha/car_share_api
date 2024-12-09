<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // Validação dos dados
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // Recuperar os dados do usuário logado
            $user = Auth::user();

            // Criar o token para o usuário logado
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login realizado com sucesso.',
                'token' => $token,
                'user' => $user,
            ], 200); // Usando o status 200 para sucesso
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login ou senha incorreta.'
            ], 401); // Status de erro apropriado para falha na autenticação
        }
    }

    public function logout(Request $request): JsonResponse
    {
        // Revogar o token de acesso
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout realizado com sucesso.'
        ], 200);
    }
}
