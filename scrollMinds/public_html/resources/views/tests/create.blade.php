@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Pregunta para el Test del Video</div>

                <div class="card-body">
                    <form action="{{ route('tests.storeQuestions', $videoId) }}" method="POST">
                        @csrf

                        <!-- Pregunta -->
                        <div class="mb-3">
                            <label for="pregunta_texto" class="form-label">Pregunta</label>
                            <input type="text" class="form-control" name="preguntas[0][texto]" id="pregunta_texto" required>
                        </div>

                        <!-- Opciones -->
                        <div class="mb-3">
                            <label for="pregunta_opcion_a" class="form-label">Opción A</label>
                            <input type="text" class="form-control" name="preguntas[0][opcion_a]" required>
                        </div>

                        <div class="mb-3">
                            <label for="pregunta_opcion_b" class="form-label">Opción B</label>
                            <input type="text" class="form-control" name="preguntas[0][opcion_b]" required>
                        </div>

                        <div class="mb-3">
                            <label for="pregunta_opcion_c" class="form-label">Opción C</label>
                            <input type="text" class="form-control" name="preguntas[0][opcion_c]" required>
                        </div>

                        <div class="mb-3">
                            <label for="pregunta_opcion_d" class="form-label">Opción D</label>
                            <input type="text" class="form-control" name="preguntas[0][opcion_d]" required>
                        </div>

                        <!-- Opción Correcta -->
                        <div class="mb-3">
                            <label for="pregunta_opcion_correcta" class="form-label">Opción Correcta</label>
                            <select class="form-select" name="preguntas[0][opcion_correcta]" required>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear Test</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
