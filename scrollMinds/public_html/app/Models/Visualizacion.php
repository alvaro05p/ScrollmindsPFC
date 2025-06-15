<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visualizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'da_like',
        'fecha'
    ];

    protected $table = 'visualizaciones';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
