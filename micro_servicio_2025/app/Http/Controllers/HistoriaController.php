<?php

namespace App\Http\Controllers;

use App\Models\Historia;
use Illuminate\Http\Request;

class HistoriaController extends Controller
{
    // Mostrar todas las historias con su sprint
    public function index()
    {
        $historias = Historia::with('sprint')->get();
        return response()->json($historias);
    }

    // Crear una historia de usuario
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'responsable' => 'required',
            'estado' => 'required|in:nueva,activa,finalizada,impedimento',
            'puntos' => 'required|integer|min:1',
            'fecha_creacion' => 'required|date',
            'sprint_id' => 'required|exists:sprints,id'
        ]);

        $historia = Historia::create($request->all());
        return response()->json(['mensaje' => 'Historia creada', 'historia' => $historia]);
    }

    // Mostrar una historia específica
    public function show($id)
    {
        $historia = Historia::with('sprint')->find($id);

        if (!$historia) {
            return response()->json(['error' => 'Historia no encontrada'], 404);
        }

        return response()->json($historia);
    }

    // Actualizar una historia
    public function update(Request $request, $id)
    {
        $historia = Historia::find($id);

        if (!$historia) {
            return response()->json(['error' => 'Historia no encontrada'], 404);
        }

        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'responsable' => 'required',
            'estado' => 'required|in:nueva,activa,finalizada,impedimento',
            'puntos' => 'required|integer|min:1',
            'fecha_creacion' => 'required|date',
            'sprint_id' => 'required|exists:sprints,id'
        ]);

        $historia->update($request->all());
        return response()->json(['mensaje' => 'Historia actualizada', 'historia' => $historia]);
    }

    // Eliminar una historia
    public function destroy($id)
    {
        $historia = Historia::find($id);

        if (!$historia) {
            return response()->json(['error' => 'Historia no encontrada'], 404);
        }

        $historia->delete();
        return response()->json(['mensaje' => 'Historia eliminada']);
    }
}