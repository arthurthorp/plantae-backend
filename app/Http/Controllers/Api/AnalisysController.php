<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Activity;

class AnalisysController extends Controller
{
    /**
     * Realiza o relatório dos insumos gastos em uma plantação
     */
    public function agriculturalInputExpenses(int $id)
    {
        $reports = Activity::join('agricultural_inputs', 'activities.agricultural_input_id', '=', 'agricultural_inputs.id')
            ->where('activities.type', 'AGRICULTURAL_INPUT')
            ->where('activities.plantation_id', $id)
            ->select(DB::raw('agricultural_inputs.type as type, SUM(activities.quantity_used) as quantityUsed, SUM(activities.price) as totalPrice'))
            ->groupBy('agricultural_inputs.type')
            ->get()
            ->toArray();

        $total = 0;

        foreach ($reports as $report) {
            $total += $report['totalPrice'];
        }

        $total = round($total, 2);

        return response()->json([
            'object' => [
                'types' => $reports,
                'total' => $total
            ],
        ], 200);
    }

    /**
     * Relatório das atividades entregues atrasadas de uma plantação
     */
    public function lateActivities(Request $request)
    {
        //
    }
}
