@extends('layouts.app')

@section('content')

 <section class="hero-section">
        <div class="floating-elements">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
        </div>
        
        <div class="container content-wrapper">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 fade-in">
                    <h1 class="hero-title mb-4">Bienvenido a ScrollMinds</h1>
                    <div class="badge bg-secondary mb-4 px-3 py-2 fs-6">Beta</div>
                    
                    <div class="feature-card">
                        <h3 class="h5 mb-3 text-muted">Sistema de Niveles</h3>
                        <p class="mb-3">Comienza en el <strong>nivel 1</strong> y evoluciona tu perfil educativo</p>
                        
                        <h4 class="h6 mb-3 text-muted">Sube de nivel mediante:</h4>
                        <ul class="feature-list">
                            <li class="feature-item">Subir contenido de video educativo</li>
                            <li class="feature-item">Responder correctamente a los test</li>
                            <li class="feature-item">Recibir likes de la comunidad</li>
                        </ul>
                    </div>
                    
                    <div class="warning-badge">
                        <strong>Importante:</strong> Queda totalmente prohibido el contenido con copyright o ilegal
                    </div>
                </div>
                
                <div class="col-lg-6 text-center fade-in">
                    <div class="image-container">
                        <img src="{{ asset('img/scrollminds.jpeg') }}" 
                             alt="Scrollminds Learning Platform" 
                             class="hero-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container py-5">
      <h2 class="text-center mb-4">¿Que es scrollMinds?</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="var(--primary-color)" class="bi bi-book" viewBox="0 0 16 16">
                  <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                </svg>
              </div>
              <h3 class="card-title h4">Aprende algo</h3>
              <p class="card-text">Gran variedad de vídeos divulgativos en los que estar atento te hace subir de nivel.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="var(--primary-color)" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                </svg>
              </div>
              <h3 class="card-title h4">Crea y comparte</h3>
              <p class="card-text">Date a conocer en nuestra plataforma como creador de contenido educativo en cualquier área.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="var(--primary-color)" class="bi bi-people" viewBox="0 0 16 16">
                  <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                </svg>
              </div>
              <h3 class="card-title h4">Únete a la comunidad</h3>
              <p class="card-text">Cada vez más usuarios usan ScrollMinds, una forma innovadora de entretenimiento.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="container py-5">
    <div class="container mt-4">
    <h2>Ranking de Usuarios</h2>
    <div class="row">
            @foreach($usuarios as $user)
                <div class="col-md-4 mb-3 d-flex align-items-center">
                    <img src="{{ asset($user->imagen_perfil ?? 'img/default.png') }}" alt="Foto de perfil" 
                        class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                        <strong>@ {{ $user->username }}</strong><br>
                        Nivel: {{ $user->nivel }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </section>

    <section class="container-fluid py-5" style="background-color: var(--background-light);">
  <div class="container">
    <h2 class="text-center mb-4">Videos populares</h2>
    <div class="row g-4">
      @forelse($topVideos as $video)
        <div class="col-md-4">
          <div class="card text-center h-100">
            <video 
              class="card-img-top" 
              playsinline
              preload="metadata" 
              style="width: 100%; height: auto; max-height: 200px; object-fit: cover;">
              <source src="{{ asset($video->ruta_video) }}" type="video/mp4">>
              Tu navegador no soporta el elemento de video.
            </video>
            <div class="card-body">
              <h5 class="card-title">{{ $video->titulo }}</h5>
              <p class="card-text">{{ Str::limit($video->descripcion, 80) }}</p>
              <p class="text-muted">Likes: {{ $video->likes_count }}</p>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center">No hay videos populares aún.</p>
      @endforelse
    </div>
  </div>
</section>


@endsection