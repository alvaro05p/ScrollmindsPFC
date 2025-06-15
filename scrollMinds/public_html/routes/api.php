<?php
// routes/api.php

use Illuminate\Support\Facades\Route;

// Si prefieres usar Sanctum en lugar de auth web, descomenta esto:
/*
use App\Http\Controllers\VideoController;
use App\Http\Controllers\TestController;

// Rutas API públicas
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos-with-test', [VideoController::class, 'getVideos']);

// Rutas API protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/videos/{video}/like', [VideoController::class, 'like']);
    Route::post('/preguntas/{pregunta}/responder', [TestController::class, 'responder']);
    Route::post('/tests/{test}/submit', [TestController::class, 'submitTest']);
});

// Verificar autenticación con Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// Por ahora, deja este archivo vacío o solo con rutas que realmente necesites para API externa