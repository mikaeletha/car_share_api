<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BorrowCarRequest;
use App\Http\Requests\Api\StoreCarRequest;
use App\Models\Car;
use App\Models\CarRental;
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
                'owner_id' => $request->userId,
                'fuel_type' => $request->fuelType,
                'status' => 'available',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
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

    public function getCarInfo($id)
    {
        try {
            $car = Car::find($id);

            if (!$car) {
                return response()->json([
                    'status' => false,
                    'message' => 'Car not found.',
                ], 404);
            }
            return response()->json([
                'status' => true,
                'cars' => $car,
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

        $car->deleted = 1;
        $car->status = 'unavailable';
        $car->save();

        return response()->json([
            'status' => true,
            'message' => 'Car removed successfully.',
        ], 200);
    }

    public function postRental(BorrowCarRequest $request): JsonResponse
    {
        $car = Car::find($request->car_id);

        // Verifica se o carro existe e está disponível
        if (!$car || $car->status !== 'available') {
            return response()->json([
                'status' => false,
                'message' => 'Car is not available or does not exist.',
            ], 400);
        }

        try {
            // Cria o registro de aluguel
            $carRental = CarRental::create([
                'car_id' => $request->car_id,
                'client_id' => $request->client_id,
                'borrowed_at' => now(),
                'borrowed_latitude' => $request->borrowed_latitude,
                'borrowed_longitude' => $request->borrowed_longitude,
            ]);

            // Atualiza o status do carro para indisponível
            $car->status = 'unavailable';
            $car->save();

            // Retorna sucesso
            return response()->json([
                'status' => true,
                'message' => 'Car borrowed successfully.',
                'data' => $carRental,
            ], 201);
        } catch (\Exception $e) {
            // Loga o erro e retorna falha
            // \Log::error('Error borrowing car', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Error borrowing car. Please try again later.',
            ], 500);
        }
    }
}
