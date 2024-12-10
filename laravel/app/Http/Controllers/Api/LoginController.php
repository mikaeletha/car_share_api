<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{

    // public function login(Request $request): JsonResponse
    // {

    //     if (Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {

    //         // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

    //         // Recuperar os dados do usuário logado
    //         $user = Auth::user();

    //         // Criar o token para o usuário logado
    //         $token = $request->user()->createToken('api-token')->plainTextToken;

    //         return response()->json([
    //             'status' => true,
    //             'token' => $token,
    //             'user' => $user,
    //         ], 201);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Login ou senha incorreta.'
    //         ], 404);
    //     }

    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     // Tentar encontrar o usuário
    //     $user = Client::where('email', $request->email)->first();

    //     // Verificar se o usuário existe e se a senha bate
    //     if ($user && Hash::check($request->password, $user->password)) {
    //         // Criar token do usuário
    //         $token = $user->createToken('api-token')->plainTextToken;

    //         return response()->json([
    //             'status' => true,
    //             'token' => $token,
    //             'user' => $user,
    //         ], 200);
    //     }

    //     // Se falhar a autenticação
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Login ou senha incorreta.'
    //     ], 401);
    // }

    public function login(Request $request): JsonResponse
    {
        // Validação de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentar encontrar o usuário
        $user = Client::where('email', $request->email)->first();

        // Verificar se o usuário existe e se a senha bate
        if ($user && Hash::check($request->password, $user->password)) {
            // Criar token do usuário
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        // Se falhar a autenticação
        return response()->json([
            'status' => false,
            'message' => 'Login ou senha incorreta.'
        ], 401);
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
