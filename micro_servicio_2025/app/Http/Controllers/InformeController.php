<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    // Informe general: cuántas historias hay por estado
    public function resumenGeneral()
    {
        $resumen = DB::table('historias')
            ->select(
                DB::raw("COUNT(CASE WHEN estado = 'finalizada' THEN 1 END) as finalizadas"),
                DB::raw("COUNT(CASE WHEN estado IN ('nueva', 'activa') THEN 1 END) as pendientes"),
                DB::raw("COUNT(CASE WHEN estado = 'impedimento' THEN 1 END) as impedimentos")
            )
            ->first();

        return response()->json($resumen);
    }

    // Informe por responsable
    public function resumenPorResponsable()
    {
        $resumen = DB::table('historias')
            ->select(
                'responsable',
                DB::raw("COUNT(CASE WHEN estado = 'finalizada' THEN 1 END) as finalizadas"),
                DB::raw("COUNT(CASE WHEN estado IN ('nueva', 'activa') THEN 1 END) as pendientes"),
                DB::raw("COUNT(CASE WHEN estado = 'impedimento' THEN 1 END) as impedimentos")
            )
            ->groupBy('responsable')
            ->get();

        return response()->json($resumen);
    }
}