<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    // Listar todos los sprints
    public function index()
    {
        $sprints = Sprint::all();
        return response()->json($sprints);
    }

    // Crear un nuevo sprint
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $sprint = Sprint::create($request->all());
        return response()->json(['mensaje' => 'Sprint creado', 'sprint' => $sprint], 201);
    }

    // Ver un sprint específico
    public function show($id)
    {
        $sprint = Sprint::find($id);

        if (!$sprint) {
            return response()->json(['error' => 'Sprint no encontrado'], 404);
        }

        return response()->json($sprint);
    }

    // Actualizar un sprint
    public function update(Request $request, $id)
    {
        $sprint = Sprint::find($id);

        if (!$sprint) {
            return response()->json(['error' => 'Sprint no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $sprint->update($request->all());
        return response()->json(['mensaje' => 'Sprint actualizado', 'sprint' => $sprint]);
    }

    // Eliminar un sprint
    public function destroy($id)
    {
        $sprint = Sprint::find($id);

        if (!$sprint) {
            return response()->json(['error' => 'Sprint no encontrado'], 404);
        }

        $sprint->delete();
        return response()->json(['mensaje' => 'Sprint eliminado']);
    }
}