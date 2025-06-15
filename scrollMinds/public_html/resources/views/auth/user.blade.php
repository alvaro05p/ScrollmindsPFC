@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Col Izquierda: Foto y botón editar imagen -->
        <div class="col-md-4 d-flex align-items-center nombre-foto">
            <div class="position-relative">
                <img src="{{ session('imagen_perfil') ? asset(session('imagen_perfil')) : asset('img/pordefecto.png') }}" alt="Foto de perfil" class="profile-photo rounded-circle">                <button type="button" class="btn btn-primary btn-sm rounded-circle p-1 editar position-absolute top-0 end-0 translate-middle" 
                    style="z-index: 10;" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                    <img src="{{ asset('img/logos/editar.png') }}" alt="editar" class="img-fluid" style="width: 16px; height: 16px;">
                </button>
            </div>

            <div class="bg-custom nombre ms-3 d-flex align-items-center">
                <h2 class="mb-0">@ {{ session('username') }}</h2>
            </div>
        </div>

        <!-- Col Derecha: Estadísticas -->
        <div class="col-md-8">
            <div class="stats d-flex justify-content-between">
                <div id="videos-subidos" class="text-center">
                    <span>Videos subidos:</span><br>
                    <p>{{ $totalVideos }}</p>
                </div>
                <div id="nivel" class="text-center">
                    <span>Nivel:</span>
                    {{-- Aquí llamamos al método actualizarNivel para actualizar el nivel en BD y mostrarlo --}}
                    <p>{{ $user->actualizarNivel() }}</p>
                </div>
                <div id="aciertos" class="text-center">
                    <span>Aciertos:</span><br>
                    <b>{{ $totalAciertos }}</b>
                </div>
            </div>

            <!-- Descripción con botón editar -->
            <div class="d-flex align-items-center bg-custom mt-3 p-3 position-relative">
                <div class="flex-grow-1">
                    {{ session('descripcion') }}
                </div>
                <button type="button" class="btn btn-primary btn-sm rounded-circle p-1 editar ms-3" 
                    data-bs-toggle="modal" data-bs-target="#exampleModal" aria-label="Editar descripción">
                    <img src="{{ asset('img/logos/editar.png') }}" alt="editar" class="img-fluid" style="width: 16px; height: 16px;">
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Col Izquierda: Cursos Finalizados (vacío por ahora) -->
        <div class="col-md-6">
            <!-- Aquí puedes añadir contenido de cursos finalizados -->
        </div>

        <!-- Col Derecha: Rango y cerrar sesión -->
        <div class="col-md-6 d-flex flex-column align-items-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger" name="cerrar">Cerrar sesión</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar descripción -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-dark" id="exampleModalLabel">Editar descripción</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('user.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="accion" value="editar_descripcion">
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="descripcion" value="{{ session('descripcion') }}" aria-describedby="descripcionHelp">
                            <div id="descripcionHelp" class="form-text text-dark">Podrás editar la descripción las veces que quieras.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" class="btn btn-primary" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar imagen -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-dark" id="exampleModalLabel2">Editar imagen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('user.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="accion" value="editar_imagen">
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="imagen_perfil" accept="image/*" aria-describedby="emailHelpImg">
                            <div id="emailHelpImg" class="form-text text-dark">Podrás editar la imagen las veces que quieras.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" class="btn btn-primary" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
