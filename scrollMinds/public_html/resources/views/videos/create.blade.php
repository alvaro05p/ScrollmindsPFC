@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Subir Video</div>

                <div class="card-body">
                    <!-- Mensaje de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Formulario para subir video -->
                    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Título -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título del video" value="{{ old('titulo') }}" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del video" rows="3">{{ old('descripcion') }}</textarea>
                        </div>

                        <!-- Video -->
                        <div class="mb-3">
                            <label for="video" class="form-label">Archivo de Video</label>
                            <input type="file" class="form-control" id="video" name="video" accept="video/*" required>
                            <small class="form-text text-muted">Formatos permitidos: MP4, MOV, WEBM (Max. 60MB)</small>
                        </div>

                        <!-- Botones -->
                        <button type="submit" class="btn btn-primary">Subir Video</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
