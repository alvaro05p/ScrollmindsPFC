<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    // Especificamos la tabla si el nombre no sigue la convención
    protected $table = 'tests';

    // Definimos los campos que son asignables masivamente
    protected $fillable = [
        'video_id',
        'fecha_subida',
    ];

    // Relación con el modelo Video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }
}
