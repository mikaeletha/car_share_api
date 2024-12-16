<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BorrowCarRequest;
use App\Http\Requests\Api\ReturnCarRequest;
use App\Models\Car;
use App\Models\CarRental;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarRentalController extends Controller
{
    public function borrow(BorrowCarRequest $request): JsonResponse
    {
        // $request['borrowed_at'] = Carbon::now();
        $request->merge(['borrowed_at' => Carbon::now()]);

        $car = Car::find($request->car_id);

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

    // public function return(Request $request, $id): JsonResponse
    // {
    //     $request->merge(['returned_at' => Carbon::now()]);

    //     // Verifica se o carro existe
    //     $car = Car::find($id);

    //     if (!$car) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Car does not exist.',
    //         ], 400);
    //     }

    //     try {
    //         // Verifica se o aluguel do carro existe
    //         $carRental = CarRental::where('car_id', $id)
    //             ->whereNull('returned_at')  // Verifica que o carro ainda não foi devolvido
    //             ->first();

    //         if (!$carRental) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Car rental record not found or already returned.',
    //             ], 400);
    //         }

    //         // Atualiza o registro de aluguel (marca a devolução)
    //         $carRental->update([
    //             'returned_at' => $request->returned_at,
    //             'returned_latitude' => $request->returned_latitude,
    //             'returned_longitude' => $request->returned_longitude,
    //         ]);

    //         // Atualiza o status do carro para 'disponível'
    //         $car->status = 'available';
    //         $car->save();

    //         // Retorna sucesso
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Car returned successfully.',
    //             'data' => $carRental,
    //         ], 200);  // Código de resposta 200 para sucesso
    //     } catch (\Exception $e) {
    //         // Retorna erro em caso de exceção
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Error returning car. Please try again later.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function return(ReturnCarRequest $request, $rental_id): JsonResponse
    {
        $carRental = CarRental::find($rental_id);

        if (!$carRental) {
            return response()->json([
                'status' => false,
                'message' => 'Rental record does not exist.',
            ], 400);
        }

        $car = Car::find($request->car_id);

        if (!$car) {
            return response()->json([
                'status' => false,
                'message' => 'Car does not exist.',
            ], 400);
        }

        try {
            $carRental->update([
                'returned_at' => Carbon::now(),
                'returned_latitude' => $request->returned_latitude,
                'returned_longitude' => $request->returned_longitude,
            ]);

            $car->status = 'available';
            $car->latitude = $request->returned_latitude;
            $car->longitude = $request->returned_longitude;
            $car->save();

            return response()->json([
                'status' => true,
                'message' => 'Car returned and coordinates updated successfully.',
                'data' => $carRental,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error returning car. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
