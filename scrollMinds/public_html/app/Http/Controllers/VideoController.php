<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class VideoController extends Controller
{

    public function index(Request $request)
    {
        $userId = auth()->id();
    
        $videos = Video::withCount([
                'visualizaciones as likes_count' => function($query) {
                    $query->where('da_like', true);
                }
            ])
            ->with(['visualizaciones' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->orderBy('fecha_subida', 'desc')
            ->paginate(10);
    
        $videos->getCollection()->transform(function($video) use ($userId) {
            return [
                'id' => $video->id,
                'titulo' => $video->titulo,
                'descripcion' => $video->descripcion,
                'ruta_video' => $video->ruta_video,
                'likes' => $video->likes_count,
                'user_has_liked' => $video->visualizaciones->isNotEmpty() 
                    ? $video->visualizaciones->first()->da_like 
                    : false
            ];
        });
    
        return $videos;
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request)
    {
        \Log::info('Entrando al método store');
    
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,mov,webm|max:60000',
        ]);
    
        try {
            // 🔽 Ajuste para Hostinger: usar ruta real fuera de /public
            $filename = time() . '_' . $request->file('video')->getClientOriginalName();
            $destination = base_path('../public_html/storage/videos');
    
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true); // crea la carpeta si no existe
            }
    
            $request->file('video')->move($destination, $filename);
    
            $videoPath = 'storage/videos/' . $filename;
    
            // Guardar en base de datos
            $video = \App\Models\Video::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'ruta_video' => $videoPath,
                'fecha_subida' => now(),
                'user_id' => auth()->id(),
            ]);
    
            return redirect()->route('tests.create', ['videoId' => $video->id])
                ->with('success', '¡Video subido correctamente! Ahora puedes crear el test.');
    
        } catch (\Exception $e) {
            \Log::error('Error al subir video: ' . $e->getMessage());
            return back()->withErrors(['video' => 'No se pudo subir el video.']);
        }
    }


    public function getVideos(Request $request)
    {
        $perPage = 5;
        $userId = auth()->id();

        $videos = Video::with([
                'test.preguntas', // 👈 Incluye test y preguntas
                'visualizaciones' => function($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->select('id', 'video_id', 'user_id', 'da_like');
                }
            ])
            ->withCount([
                'visualizaciones as likes_count' => function ($query) {
                    $query->where('da_like', true);
                }
            ])
            ->orderBy('fecha_subida', 'desc')
            ->paginate($perPage);

        // Transformar la colección
        $videos->getCollection()->transform(function ($video) use ($userId) {
            
            return [
                'id' => $video->id,
                'titulo' => $video->titulo,
                'descripcion' => $video->descripcion,
                'ruta_video' => $video->ruta_video,
                'likes' => $video->likes_count,
                'user_has_liked' => $video->visualizaciones->isNotEmpty()
                    ? $video->visualizaciones->first()->da_like
                    : false,
                'test' => $video->test ? [
                    'id' => $video->test->id,
                    'preguntas' => $video->test->preguntas->map(function ($pregunta) {
                        return [
                            'id' => $pregunta->id,
                            'texto_pregunta' => $pregunta->texto_pregunta,
                            'opciones' => $pregunta->opciones,
                            'opcion_correcta' => $pregunta->opcion_correcta, // <-- Aquí agregamos la respuesta correcta
                        ];
                    })
                ] : null
            ];
        });

        return response()->json($videos);
    }



    public function show($videoId)
    {
        // Obtener el video por ID
        $video = Video::findOrFail($videoId);

        // Contar los likes
        $likes = $video->visualizaciones()->where('da_like', true)->count();

        // Retornar la vista con el video y el número de likes
        return view('videos.show', compact('video', 'likes'));
    }

    public function like($videoId)
    {
        try {
            // Verificar autenticación
            if (!auth()->check()) {
                return response()->json([
                    'error' => 'Debes iniciar sesión para dar like',
                    'login_required' => true
                ], 401);
            }

            $userId = auth()->id();
            $video = Video::findOrFail($videoId);

            // Buscar like existente con una sola consulta
            $existingLike = $video->visualizaciones()
                                ->where('user_id', $userId)
                                ->first();

            $liked = false; // Estado final del like
            
            if ($existingLike) {
                // Cambiar el estado del like
                $liked = !$existingLike->da_like;
                $existingLike->update(['da_like' => $liked]);
            } else {
                // Crear nuevo like
                $video->visualizaciones()->create([
                    'user_id' => $userId,
                    'da_like' => true,
                    'fecha' => now(),
                ]);
                $liked = true;
            }

            // Obtener conteo actualizado de likes
            $likesCount = $video->visualizaciones()
                            ->where('da_like', true)
                            ->count();

            return response()->json([
                'likes' => $likesCount,
                'liked' => $liked,
                'video_id' => $videoId
            ]);

        } catch (\Throwable $e) {
            Log::error('Error en like(): ' . $e->getMessage(), [
                'video_id' => $videoId,
                'user_id' => auth()->id() ?? 'guest',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al procesar el like',
                'message' => $e->getMessage()
            ], 500);
        }
    }





}
