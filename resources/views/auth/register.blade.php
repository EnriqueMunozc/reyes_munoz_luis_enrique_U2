@extends('layouts.app')

@section('title', 'Crear cuenta | Grand Line Store')

@section('content')
    <section class="auth-shell">
        <div class="auth-panel">
            <p class="eyebrow">Cuenta de tripulacion</p>
            <h1>Crea tu cuenta</h1>
            <p>Guarda tus datos para acceder a la tienda.</p>

            @if ($errors->any())
                <div class="form-alert" role="alert">
                    <p>Revisa los datos marcados e intentalo de nuevo.</p>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('register.store') }}">
                @csrf

                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
                @error('name')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <label for="email">Correo electronico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <label for="password">Contrasena</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <label for="password_confirmation">Confirmar contrasena</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>

                <button class="button primary" type="submit">Crear cuenta</button>
            </form>
        </div>
    </section>
@endsection
