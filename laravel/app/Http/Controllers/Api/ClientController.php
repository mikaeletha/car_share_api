<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ClientRequest;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(): JsonResponse
    {
        $users = Client::orderBy('name', 'ASC')->get();
        // $users = Client::orderBy('name', 'ASC')->paginate(10);

        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(ClientRequest $request)
    {
        DB::beginTransaction();
        $password = Hash::make($request->password);

        try {

            $user = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'User registered successfully!',
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'User not registered!',
            ], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = Client::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        try {
            $user->update([
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Location updated successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error! Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
