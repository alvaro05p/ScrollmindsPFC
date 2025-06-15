<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Visualizacion;
use App\Models\Realiza;
use App\Models\Video;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'descripcion',
        'imagen_perfil',
        'nivel',               // Asegúrate de agregar nivel aquí para que se pueda guardar
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones

    public function visualizaciones()
    {
        return $this->hasMany(Visualizacion::class, 'user_id');
    }

    public function realiza()
    {
        return $this->hasMany(Realiza::class, 'user_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'user_id');
    }

    // Calcular total de aciertos sumando el campo puntuacion de la relación realiza
    public function totalAciertos()
    {
        return $this->realiza()->sum('puntuacion');
    }

    // Calcular y actualizar el nivel basado en la fórmula
    public function actualizarNivel()
    {
        $aciertos = $this->totalAciertos();
        $videosSubidos = $this->videos()->count();

        $likesTotales = Visualizacion::whereIn('video_id', $this->videos()->pluck('id'))
                            ->where('da_like', true)
                            ->count();

        $puntos = ($aciertos * 1.5) + ($likesTotales * 1) + ($videosSubidos * 2);

        $nuevoNivel = floor($puntos); // puedes ajustar divisor o fórmula aquí

        if ($this->nivel !== $nuevoNivel) {
            $this->nivel = $nuevoNivel;
            $this->save();
        }

        return $this->nivel;
    }
}
