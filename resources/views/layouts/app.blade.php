<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Grand Line Store'))</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="page-shell">
            <header class="site-header">
                <nav class="nav-wrap" aria-label="Navegacion principal">
                    <a class="brand" href="{{ route('home') }}">
                        <span class="brand-mark">GL</span>
                        <span>
                            <strong>Grand Line Store</strong>
                            <small>Anime goods en MXN</small>
                        </span>
                    </a>

                    <div class="nav-links">
                        <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Inicio</a>
                        <a href="{{ route('catalog.index') }}" @class(['active' => request()->routeIs('catalog.*')])>Catalogo</a>
                    </div>
                </nav>
            </header>

            <main>
                @yield('content')
            </main>

            <footer class="site-footer">
                <div>
                    <strong>Grand Line Store</strong>
                    <p>Tienda ficticia escolar. Los pedidos, pagos y administracion se implementaran en fases posteriores.</p>
                </div>
                <p>Laravel, Blade y SQL Server.</p>
            </footer>
        </div>
    </body>
</html>
