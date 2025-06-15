<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Crea un campo 'id' autoincremental
            $table->string('username');  // Crea el campo 'username'
            $table->string('email')->unique();  // Crea el campo 'email', con restricción única
            $table->string('password');  // Crea el campo 'password'
            $table->string('imagen_perfil')->nullable();  // Crea el campo 'imagen_perfil', puede ser null
            $table->enum('tipo_usuario', ['admin', 'editor', 'viewer']);  // Crea un campo 'tipo_usuario', con valores definidos
            $table->text('descripcion')->nullable();  // Crea el campo 'descripcion', puede ser null
            $table->timestamps();  // Crea los campos 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');  // Elimina la tabla 'users' si ya existe
    }
}
