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
    public function borrow(BorrowCarRequest $request)
    {
        $request->merge(['borrowed_at' => Carbon::now()]);

        $car = Car::find($request->car_id);

        if (!$car || $car->status !== 'available') {
            return response()->json([
                'status' => false,
                'message' => 'Car is not available or does not exist.',
            ], 400);
        }

        try {
            $carRental = CarRental::create([
                'car_id' => $request->car_id,
                'client_id' => $request->client_id,
                'borrowed_at' => now(),
                'borrowed_latitude' => $request->borrowed_latitude,
                'borrowed_longitude' => $request->borrowed_longitude,
            ]);

            $car->status = 'unavailable';
            $car->save();

            return response()->json([
                'status' => true,
                'message' => 'Car borrowed successfully.',
                'data' => $carRental,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error borrowing car. Please try again later.',
            ], 500);
        }
    }

    // public function return(ReturnCarRequest $request, $rental_id)
    public function return(ReturnCarRequest $request)
    {
        $carRental = CarRental::find($request->rental_id);

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

    // public function listBorrowedCars($userId)
    // {
    //     $borrowedCars = CarRental::with('car')
    //         ->where('client_id', $userId)
    //         ->whereNull('returned_at')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'cars' => $borrowedCars,
    //     ]);
    // }

    public function listBorrowedCars($userId)
    {
        // Obtém os carros emprestados onde "returned_at" é null
        $borrowedCars = CarRental::with('car')
            ->where('client_id', $userId)
            ->whereNull('returned_at')
            ->get();

        // Mapeia os dados dos carros emprestados para a estrutura desejada
        $responseCars = $borrowedCars->map(function ($rental) {
            return [
                'id' => $rental->car->id,
                'model' => $rental->car->model,
                'manufacturer' => $rental->car->manufacturer,
                'year' => $rental->car->year,
                'owner_id' => $rental->car->owner_id,
                'fuel_type' => $rental->car->fuel_type,
                'status' => $rental->car->status,
                'created_at' => $rental->car->created_at,
                'updated_at' => $rental->car->updated_at,
                'latitude' => $rental->car->latitude,
                'longitude' => $rental->car->longitude,
                'deleted' => $rental->car->deleted,
                'rental_id' => $rental->id,
            ];
        });

        return response()->json([
            'status' => true,
            'cars' => $responseCars,
        ]);
    }
}
