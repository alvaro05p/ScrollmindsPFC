<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ScrollMinds - Learn and Create</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="icon" href="/img/logos/2.png" type="image/x-icon">

  </head>
  <body>

    @include('partials.header')

    <main>
      @yield('content')
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @php
      $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
      $appJs = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp

    @if ($appJs)
      <script type="module" src="{{ asset('public/build/' . $appJs) }}"></script>
    @endif

  </body>
</html>
