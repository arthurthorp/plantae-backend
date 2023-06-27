<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Plantation;
use App\Models\Activity;
use App\Helpers\Helpers;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $plantationId = $request->query("plantation");

        if($plantationId) {
            $plantation = Plantation::find($plantationId);

            if(!$plantation){
                return response()->json([
                    'message' => 'A plantação não existe no sistema'
                ], 404);
            }

            return response()->json([
                'objects' => Helpers::convertToCamelCase($plantation->activities->toArray()),
            ], 200);
        }

        $activities = Activity::join('plantations_users', 'activities.plantation_id', '=', 'plantations_users.plantation_id')
        ->where('plantations_users.user_id', $request->user()->id)
        ->select('activities.*')
        ->get();

        return response()->json([
            'objects' => Helpers::convertToCamelCase($activities->toArray()),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateActivity = Validator::make($request->all(),
            [
                'description' => 'required',
                'type' => 'required',
                'status' => 'required',
                'estimateDate' => 'required|date_format:Y-m-d',
                'chargeIn' => 'required',
                'plantationId' => 'required'
            ]);

            if($validateActivity->fails()){
                return response()->json([
                    'message' => 'Campos inválidos',
                    'errors' => $validateActivity->errors()
                ], 400);
            }

            $plantation = Plantation::find($request->plantationId);

            if(!$plantation){
                return response()->json([
                    'message' => 'A plantação não existe no sistema'
                ], 404);
            }

            $activity = Activity::create([
                'description' => $request->description,
                'type' => $request->type,
                'status' => $request->status,
                'estimate_date' => $request->estimateDate,
                'charge_in' => $request->chargeIn,
                'plantation_id' => $request->plantationId,
                'estimate_produtivity' => $request->estimateProdutivity,
                'real_produtivity' => $request->realProdutivity,
                'agricultura_input_id' => $request->agriculturalInputId,
                'quantity_used' => $request->quantityUsed,
                'price' => $request->price
            ]);


        } catch(\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Atividade criada com sucesso!',
            'object' => Helpers::convertToCamelCase($activity->toArray())
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

        $activity = Activity::find($id);

        return response()->json([
            'object' => Helpers::convertToCamelCase($activity->toArray()),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validateActivity = Validator::make($request->all(),
            [
                'description' => 'required',
                'type' => 'required',
                'status' => 'required',
                'estimateDate' => 'required|date_format:Y-m-d',
                'chargeIn' => 'required',
                'plantationId' => 'required'
            ]);

            if($validateActivity->fails()){
                return response()->json([
                    'message' => 'Campos inválidos',
                    'errors' => $validateActivity->errors()
                ], 400);
            }

            $activity = Activity::find($id);

            $activity->description = $request->description;
            $activity->type = $request->type;
            $activity->status = $request->status;
            $activity->estimate_date = $request->estimateDate;
            $activity->charge_in = $request->chargeIn;
            $activity->plantation_id = $request->plantationId;
            $activity->estimate_produtivity = $request->estimateProdutivity;
            $activity->real_produtivity = $request->realProdutivity;
            $activity->agricultura_input_id = $request->agriculturalInputId;
            $activity->quantity_used = $request->quantityUsed;
            $activity->price = $request->price;

            $activity->save();


        } catch(\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Atividade alterada com sucesso!',
            'object' => Helpers::convertToCamelCase($activity->toArray())
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $activity = Activity::find($id);
            $activity->delete();

        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response(null,204);
    }

    public function finish(string $id)
    {
        try {
            $activity = Activity::find($id);
            $activity->status = 'FINISHED';
            $activity->execution_date = date('Y-m-d');
            $activity->save();

        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Atividade finalizada com sucesso!',
            'object' => Helpers::convertToCamelCase($activity->toArray())
        ], 200);
    }
}
