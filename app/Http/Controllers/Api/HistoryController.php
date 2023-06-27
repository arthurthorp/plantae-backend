<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\History;
use App\Helpers\Helpers;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activityId = $request->route('id');

        $activity = Activity::find($activityId);

        $histories = $activity->histories()->orderBy('created_at', 'desc')->get();

        foreach ($histories as $history) {
            $history->getImagePath();
        }

        return response()->json([
            'objects' => Helpers::convertToCamelCase($histories->toArray()),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        try {
            $validateHistory = Validator::make($request->all(),
            [
                'description' => 'required',
                'image' => 'required'
            ]);

            if($validateHistory->fails()){
                return response()->json([
                    'message' => 'Campos inválidos',
                    'errors' => $validateHistory->errors()
                ], 400);
            }

            $history = History::create([
                'description' => $request->description,
                'activity_id' => $id,
                'is_impediment' => !!$request->isImpediment
            ]);

            $path = $request->file('image')->store('history/'.$history->id, 'public');

            $history->image_path = $path;
            $history->save();

            $history->image_path = asset('storage/'.$path);

            if($request->isImpediment) {
                $activity = Activity::find($id);
                $activity->status = 'FORBIDDEN';
                $activity->save();
            }

        }catch(\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => $request->isImpediment ? 'Impedimento adicionado com sucesso!' : 'Execução adicionada com sucesso!',
            'object' => Helpers::convertToCamelCase($history->toArray())
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $activity, string $id)
    {
        return response()->json([
            'object' => Helpers::convertToCamelCase(History::find($id)->getImagePath()->toArray()),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => "Função não implementada"
        ], 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $history = History::find($id);
            $history->delete();

        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
        return response(null,204);
    }
}
