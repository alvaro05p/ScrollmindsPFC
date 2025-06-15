<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'ruta_video',
        'fecha_subida',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visualizaciones()
    {
        return $this->hasMany(Visualizacion::class);
    }

    public function test()
    {
        return $this->hasOne(Test::class);
    }

    public function likes()
    {
        return $this->hasMany(Visualizacion::class)->where('da_like', true);
    }
    
}
