@extends('layouts.app')

@section('title', 'Iniciar sesion | Grand Line Store')

@section('content')
    <section class="auth-shell">
        <div class="auth-panel">
            <p class="eyebrow">Cuenta de tripulacion</p>
            <h1>Iniciar sesion</h1>

            @if ($errors->any())
                <div class="form-alert" role="alert">
                    <p>Revisa tus datos e intentalo de nuevo.</p>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('login.store') }}">
                @csrf

                <label for="email">Correo electronico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <label for="password">Contrasena</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <button class="button primary" type="submit">Iniciar sesion</button>
            </form>
        </div>
    </section>
@endsection
