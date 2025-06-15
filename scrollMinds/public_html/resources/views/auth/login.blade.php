<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scrollmids - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<div class="padding_form">
  <div class="d-flex align-items-center justify-content-center gap-3">
        <img class="logo logo-login" src="img/logos/3.png" alt="">
        <h1>Bienvenido a Scrollminds</h1>
  </div>
      <form action="{{ route('login') }}" method="POST" autocomplete="on">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" name="username" id="username" autocomplete="username" value="{{ old('username') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" autocomplete="current-password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>
            <input type="submit" class="btn-primary text-white" name="entrar" value="Entrar">
            <br>
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate aquí</a>
        
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif
        </form>

</div>
