<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCarRequest;
use App\Models\Car;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cars = Car::orderBy('model', 'ASC')->get();

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
                'message' => 'Carro criado com sucesso!'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Carro não cadastrado!',
                // 'error' => $e->getMessage(),
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
    public function destroy(string $id)
    {
        //
    }
}
