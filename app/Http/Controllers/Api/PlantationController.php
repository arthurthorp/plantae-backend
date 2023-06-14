<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plantation;
use Illuminate\Support\Facades\Validator;

class PlantationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'objects' => $user->plantations,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $validatePlantation = Validator::make($request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'cultivation' => 'required',
                'planting_date' => 'required|date_format:Y-m-d',
                'estimate_harvest_date' => 'required|date_format:Y-m-d',
                'plantation_size' => 'required|decimal:2'
            ]);

            if($validatePlantation->fails()){
                return response()->json([
                    'message' => 'Campos inválidos',
                    'errors' => $validatePlantation->errors()
                ], 400);
            }

            $plantation = Plantation::create([
                'name' => $request->name,
                'description' => $request->description,
                'cultivation' => $request->cultivation,
                'planting_date' => $request->planting_date,
                'estimate_harvest_date' => $request->estimate_harvest_date,
                'plantation_size' => $request->plantation_size
            ]);

            $plantation->users()->attach($user->id);

            return response()->json([
                'message' => 'Plantação criada com sucesso!',
                'object' => $plantation
            ], 201);

        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();

        $plantation = $user->plantations()->find($id);

        if(!$plantation) {
            return response()->json([
                'message' => "Você não possui permissão para acessar essa plantação",
            ], 401);
        }

        return response()->json([
            'object' => $plantation,
        ], 200);
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
