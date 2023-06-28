<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Plantation;
use App\Models\Activity;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $plantationId = $request->query("plantation");

        if($plantationId) {
            $activities = Activity::with('user')->where('plantation_id', $plantationId)->get();

        }else {
            $activities = Activity::with('user')->join('plantations_users', 'activities.plantation_id', '=', 'plantations_users.plantation_id')
                ->where('plantations_users.user_id', $request->user()->id)
                ->select('activities.*')
                ->get();
        }

        foreach ( $activities as $activity) {
            if($activity->image_path)
                $activity->getImagePath();
        }


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
                'agricultural_input_id' => $request->agriculturalInputId,
                'quantity_used' => $request->quantityUsed,
                'price' => $request->price
            ]);

            if($request->file('image')) {
                $path = $request->file('image')->store('activity/'.$activity->id, 'public');
                $activity->image_path = $path;
                $activity->save();
                $activity->getImagePath();
            }


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
    public function show(string $id)
    {
        $activity = Activity::with('user')->with(['histories' => function($query) {
            $query->orderBy('created_at', 'DESC');
        }])->find($id);

        if($activity->image_path)
            $activity->getImagePath();

        foreach ( $activity->histories as $history) {
            $history->getImagePath();
        }

        return response()->json([
            'object' => Helpers::convertToCamelCase($activity->toArray()),
        ], 200);
    }

    public function resume(string $id)
    {
        $irrigation = Activity::where('type', 'IRRIGATION')
            ->where('plantation_id', $id)
            ->where('status', 'FINISHED')
            ->select(DB::raw('id, type, execution_date'))
            ->orderBy('execution_date', 'DESC')
        ->first();

        $input = Activity::where('type', 'AGRICULTURAL_INPUT')
            ->where('plantation_id', $id)
            ->where('status', 'FINISHED')
            ->select(DB::raw('id, type, execution_date'))
            ->orderBy('execution_date', 'DESC')
        ->first();

        $paring = Activity::where('type', 'PARING')
            ->where('plantation_id', $id)
            ->where('status', 'FINISHED')
            ->select(DB::raw('id, type, execution_date'))
            ->orderBy('execution_date', 'DESC')
        ->first();

        $list = Activity::where('status', 'FINISHED')
            ->where('plantation_id', $id)
            ->orderBy('execution_date', 'DESC')
            ->select(DB::raw('id, description, type, execution_date'))
            ->limit(10)
        ->get();

        return response()->json([
            'object' => [
                'irrigation' => Helpers::convertToCamelCase($irrigation ? $irrigation->toArray() : null),
                'agricultural_input' => Helpers::convertToCamelCase($input ? $input->toArray(): null),
                'paring' => Helpers::convertToCamelCase($paring ? $paring->toArray(): null),
                'list' => Helpers::convertToCamelCase($list->toArray())
            ]
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

            if($request->file('image')) {
                if($activity->image_path)
                    Storage::delete('public/'.$activity->image_path);

                $path = $request->file('image')->store('activity/'.$activity->id, 'public');
                $activity->image_path = $path;
                $activity->save();
                $activity->getImagePath();
            }


            $activity->description = $request->description;
            $activity->type = $request->type;
            $activity->status = $request->status;
            $activity->estimate_date = $request->estimateDate;
            $activity->charge_in = $request->chargeIn;
            $activity->plantation_id = $request->plantationId;
            $activity->estimate_produtivity = $request->estimateProdutivity;
            $activity->real_produtivity = $request->realProdutivity;
            $activity->agricultural_input_id = $request->agriculturalInputId;
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
            $activity = Activity::with('user')->find($id);
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
