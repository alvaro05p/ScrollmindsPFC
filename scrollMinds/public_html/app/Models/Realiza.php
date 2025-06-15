<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realiza extends Model
{
    protected $table = 'realiza';

    protected $fillable = [
        'user_id',
        'test_id',
        'puntuacion',
        'fecha_test',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

}