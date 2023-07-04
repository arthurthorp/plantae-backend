<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plantation;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helpers;

class PlantationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'objects' => Helpers::convertToCamelCase($user->plantations->toArray()),
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
                'plantingDate' => 'required|date_format:Y-m-d',
                'estimateHarvestDate' => 'required|date_format:Y-m-d',
                'plantationSize' => 'required|decimal:2'
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
                'planting_date' => $request->plantingDate,
                'estimate_harvest_date' => $request->estimateHarvestDate,
                'plantation_size' => $request->plantationSize
            ]);

            $plantation->users()->attach($user->id);



        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Plantação criada com sucesso!',
            'object' => Helpers::convertToCamelCase($plantation->toArray())
        ], 201);
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
            'object' => Helpers::convertToCamelCase($plantation->toArray()),
        ], 200);
    }

    public function associates(Request $request, string $id)
    {
        $plantation = Plantation::find($id);

        $users = $plantation->users()->where('users.is_owner', 0)->get();

        return response()->json([
            'object' => Helpers::convertToCamelCase($users->toArray()),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        $plantation = $user->plantations()->find($id);

        if(!$plantation) {
            return response()->json([
                'message' => "Você não possui permissão para acessar essa plantação",
            ], 401);
        }

        try {
            $validatePlantation = Validator::make($request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'cultivation' => 'required',
                'plantingDate' => 'required|date_format:Y-m-d',
                'estimateHarvestDate' => 'required|date_format:Y-m-d',
                'plantationSize' => 'required|decimal:2'
            ]);

            if($validatePlantation->fails()){
                return response()->json([
                    'message' => 'Campos inválidos',
                    'errors' => $validatePlantation->errors()
                ], 400);
            }

            $plantation->name = $request->name;
            $plantation->description = $request->description;
            $plantation->cultivation = $request->cultivation;
            $plantation->planting_date = $request->plantingDate;
            $plantation->estimate_harvest_date = $request->estimateHarvestDate;
            $plantation->plantation_size = $request->plantationSize;
            $plantation->save();

        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Plantação atualizada com sucesso!',
            'object' => Helpers::convertToCamelCase($plantation->toArray())
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        $plantation = $user->plantations()->find($id);

        if(!$plantation) {
            return response()->json([
                'message' => "Você não possui permissão para acessar essa plantação",
            ], 401);
        }

        try {
            $plantation->users()->detach();
            $plantation->delete();
        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response(null,204);
    }
}
