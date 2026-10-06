@extends('layouts.app')

@section('title', 'Administracion | Grand Line Store')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Area protegida</p>
        <h1>Panel administrativo de Grand Line Store</h1>
        <p>Acceso reservado para cuentas administradoras.</p>
    </section>

    <section class="section-wrap">
        <div class="admin-overview">
            <a class="admin-metric" href="{{ route('admin.categories.index') }}">
                <span>Categorias</span>
                <strong>{{ $metrics['categories'] }}</strong>
                <small>Gestionar categorias</small>
            </a>
            <a class="admin-metric" href="{{ route('admin.products.index') }}">
                <span>Productos</span>
                <strong>{{ $metrics['products'] }}</strong>
                <small>Gestionar productos</small>
            </a>
            <div class="admin-metric">
                <span>Disponibles</span>
                <strong>{{ $metrics['activeProducts'] }}</strong>
                <small>Productos activos</small>
            </div>
        </div>

        <div class="admin-shortcuts">
            <a class="button primary" href="{{ route('admin.products.create') }}">Nuevo producto</a>
            <a class="button ghost" href="{{ route('admin.categories.create') }}">Nueva categoria</a>
        </div>
    </section>
@endsection
