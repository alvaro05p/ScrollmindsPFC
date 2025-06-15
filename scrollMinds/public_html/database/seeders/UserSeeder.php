<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear un usuario manualmente:
        User::create([
            'username' => 'alvaroseller',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'), // Nunca guardes texto plano
        ]);

    }
}
