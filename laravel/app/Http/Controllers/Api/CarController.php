<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCarRequest;
use App\Models\Car;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(): JsonResponse
    {
        $cars = Car::where('status', 'available')
            ->orderBy('model', 'ASC')
            ->get();

        return response()->json([
            'status' => true,
            'cars' => $cars,
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        DB::beginTransaction();

        try {

            $car = Car::create([
                'model' => $request->model,
                'manufacturer' => $request->manufacturer,
                'year' => $request->year,
                'owner_id' => $request->owner_id,
                'fuel_type' => $request->fuel_type,
                'status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'car' => $car,
                'message' => 'Car successfully created!'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Car not registered!',
                // 'error' => $e->getMessage(),
            ], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            // Busca os carros pelo `owner_id` fornecido
            $cars = Car::where('owner_id', $id)
                ->orderBy('model', 'ASC')
                ->get();

            // Verifica se há carros para o dono
            if ($cars->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No cars found for this owner!',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'cars' => $cars,
            ], 200);
        } catch (Exception $e) {
            // Captura erros inesperados
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving cars.',
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove($id): JsonResponse
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'status' => false,
                'message' => 'Car not found.',
            ], 404);
        }

        // if ($car->owner_id != Auth::id()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'You are not the owner of this car.',
        //     ], 403);
        // }

        $car->deleted = 1;
        $car->status = 'unavailable';
        $car->save();

        return response()->json([
            'status' => true,
            'message' => 'Car removed successfully.',
        ], 200);
    }
}
