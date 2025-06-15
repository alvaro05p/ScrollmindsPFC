<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use App\Models\Realiza;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación WEB
     */
    public function __construct()
    {
        // Usar middleware 'auth' para autenticación web en lugar de 'auth:sanctum'
        $this->middleware('auth')->only(['submitTest', 'responder']);
    }

    /**
     * Mostrar formulario de creación de preguntas para un video.
     */
    public function create($videoId)
    {
        return view('tests.create', compact('videoId'));
    }

    /**
     * Guardar las preguntas del test para un video.
     */
    public function storeQuestions(Request $request, $videoId)
    {
        $request->validate([
            'preguntas.0.texto' => 'required|string',
            'preguntas.0.opcion_a' => 'required|string',
            'preguntas.0.opcion_b' => 'required|string',
            'preguntas.0.opcion_c' => 'required|string',
            'preguntas.0.opcion_d' => 'required|string',
            'preguntas.0.opcion_correcta' => 'required|in:a,b,c,d',
        ]);

        $test = Test::firstOrCreate(
            ['video_id' => $videoId],
            ['fecha_subida' => now()]
        );

        // Guardar todas las preguntas recibidas
        foreach ($request->preguntas as $preguntaData) {
            $pregunta = new Pregunta([
                'texto_pregunta'   => $preguntaData['texto'],
                'opcion_a'         => $preguntaData['opcion_a'],
                'opcion_b'         => $preguntaData['opcion_b'],
                'opcion_c'         => $preguntaData['opcion_c'],
                'opcion_d'         => $preguntaData['opcion_d'],
                'opcion_correcta'  => $preguntaData['opcion_correcta'],
            ]);

            $test->preguntas()->save($pregunta);
        }

        return redirect()->route('scroll.index')->with('success', 'Test creado con éxito.');
    }

    /**
     * Verificar si una sola respuesta es correcta (uso individual).
     */
    public function responder(Request $request, Pregunta $pregunta)
    {
        $request->validate([
            'respuesta' => 'required|string|in:a,b,c,d',
        ]);

        $esCorrecta = $pregunta->opcion_correcta === $request->respuesta;

        return response()->json([
            'correcta' => $esCorrecta,
            'mensaje' => $esCorrecta ? '¡Respuesta correcta!' : 'Respuesta incorrecta.',
        ]);
    }

    public function submitTest(Request $request, $testId)
    {
        try {
            Log::info('submitTest llamado', [
                'testId' => $testId,
                'user_id' => auth()->id(),
                'authenticated' => auth()->check(),
                'session_id' => session()->getId(),
                'data' => $request->all()
            ]);

            // Verificar autenticación WEB
            if (!auth()->check()) {
                Log::warning('Usuario no autenticado en submitTest', [
                    'session_data' => session()->all(),
                    'cookies' => $request->cookies->all()
                ]);
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $request->validate([
                'respuestas' => 'required|array',
                'respuestas.*' => 'required|string|in:A,B,C,D,a,b,c,d',
            ]);

            $user = auth()->user();
            $respuestas = $request->input('respuestas');

            $test = Test::with('preguntas')->findOrFail($testId);

            $puntuacion = 0;
            foreach ($test->preguntas as $pregunta) {
                $respuestaCorrecta = strtoupper($pregunta->opcion_correcta);
                $respuestaUsuario = strtoupper($respuestas[$pregunta->id] ?? '');

                if ($respuestaUsuario && $respuestaUsuario === $respuestaCorrecta) {
                    $puntuacion++;
                }
            }

            // Verificar si ya existe un resultado para este usuario y test
            $realizaExistente = Realiza::where('user_id', $user->id)
                                     ->where('test_id', $testId)
                                     ->first();

            if ($realizaExistente) {
                $realizaExistente->update([
                    'puntuacion' => $puntuacion,
                    'fecha_test' => now(),
                ]);
            } else {
                Realiza::create([
                    'user_id' => $user->id,
                    'test_id' => $testId,
                    'puntuacion' => $puntuacion,
                    'fecha_test' => now(),
                ]);
            }

            Log::info('Test guardado correctamente', [
                'user_id' => $user->id,
                'test_id' => $testId,
                'puntuacion' => $puntuacion
            ]);

            return response()->json([
                'message' => 'Test guardado correctamente',
                'puntuacion' => $puntuacion,
                'total_preguntas' => $test->preguntas->count(),
                'porcentaje' => round(($puntuacion / $test->preguntas->count()) * 100, 2)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en submitTest', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            return response()->json(['error' => 'Datos inválidos', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error en submitTest', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}