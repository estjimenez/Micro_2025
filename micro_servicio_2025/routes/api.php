<?php

use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\InformeController;
use Illuminate\Support\Facades\Route;

Route::prefix("app")->group(function() {
    // Rutas para historias de usuario
    Route::controller(HistoriaController::class)->group(function(){
        Route::get("/historias", "index");
        Route::post("/historias", "store");
        Route::get("/historias/{id}", "show");
        Route::put("/historias/{id}", "update");
        Route::delete("/historias/{id}", "destroy");
    });

    // Rutas para sprints
    Route::controller(SprintController::class)->group(function(){
        Route::get("/sprints", "index");
        Route::post("/sprints", "store");
        Route::get("/sprints/{id}", "show");
        Route::put("/sprints/{id}", "update");
        Route::delete("/sprints/{id}", "destroy");
    });

    // Rutas para informes
    Route::controller(InformeController::class)->group(function(){
        Route::get("/informes/general", "resumenGeneral");
        Route::get("/informes/responsable", "resumenPorResponsable");
    });
});