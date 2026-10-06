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

                    <div class="nav-actions">
                        <div class="nav-links">
                            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Inicio</a>
                            <a href="{{ route('catalog.index') }}" @class(['active' => request()->routeIs('catalog.*')])>Catalogo</a>
                        </div>

                        <div class="account-links">
                            @guest
                                <a href="{{ route('login') }}" @class(['active' => request()->routeIs('login')])>Iniciar sesion</a>
                                <a class="account-register" href="{{ route('register') }}" @class(['active' => request()->routeIs('register')])>Registrarse</a>
                            @else
                                <span class="nav-user">{{ auth()->user()->name }}</span>

                                @if (auth()->user()->isAdministrator())
                                    <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.*')])>Administracion</a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="nav-logout" type="submit">Cerrar sesion</button>
                                </form>
                            @endguest
                        </div>
                    </div>
                </nav>
            </header>

            <main>
                @if (session('status'))
                    <div class="flash-message" role="status">{{ session('status') }}</div>
                @endif

                @yield('content')
            </main>

            <footer class="site-footer">
                <div>
                    <strong>Grand Line Store</strong>
                    <p>Tienda ficticia escolar. Los pedidos y pagos se implementaran en fases posteriores.</p>
                </div>
                <p>Laravel, Blade y SQL Server.</p>
            </footer>
        </div>
    </body>
</html>
