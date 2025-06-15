<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a href="{{ url('/home') }}"><img src="{{ asset('img/logos/2.png') }}" class="logo"></a>
        <span class="navbar-brand mb-0 h1"><a href="{{ url('/videos/create') }}"><button type="button" class="btn btn-outline-primary">Crear</button></a></span>
        <span class="navbar-brand mb-0 h1"><a href="{{ route('scroll.index') }}"><button type="button" class="btn btn-outline-primary">Scroll</button></a></span>
        <span class="navbar-brand mb-0 h1"><a href="{{ url('/user') }}"><button type="button" class="btn btn-outline-primary">Mi Perfil</button></a></span>
    </div>
</nav>