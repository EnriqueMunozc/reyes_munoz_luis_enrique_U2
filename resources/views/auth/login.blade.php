@extends('layouts.app')

@section('title', 'Iniciar sesion | Grand Line Store')

@section('content')
    <section class="auth-shell">
        <div class="auth-panel">
            <p class="eyebrow">Cuenta de tripulacion</p>
            <h1>Iniciar sesion</h1>
            <p>El acceso con correo y contrasena se habilitara en el siguiente bloque.</p>
            <a class="button ghost" href="{{ route('catalog.index') }}">Volver al catalogo</a>
        </div>
    </section>
@endsection
