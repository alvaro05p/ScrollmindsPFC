<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'texto_pregunta',
        'opcion_a',
        'opcion_b',
        'opcion_c',
        'opcion_d',
        'opcion_correcta',
    ];

    // Relación con el modelo Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    protected $appends = ['opciones'];

    public function getOpcionesAttribute()
    {
        return [
            $this->opcion_a,
            $this->opcion_b,
            $this->opcion_c,
            $this->opcion_d,
        ];
    }
}
