<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgriculturalInput;

class AgriculturalInputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inputs = AgriculturalInput::orderByRaw("FIELD(type, 'FERTILIZER','FUNGICIDE', 'HERBICIDE', 'OTHER'), name ASC")->get();

        return response()->json([
            'objects' => $inputs
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
