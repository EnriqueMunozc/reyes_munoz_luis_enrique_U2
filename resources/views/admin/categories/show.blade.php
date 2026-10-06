@extends('layouts.app')

@section('title', $category->name . ' | Administracion')

@section('content')
    <section class="page-hero compact"><p class="eyebrow">Categoria</p><h1>{{ $category->name }}</h1><p>{{ $category->description ?: 'Sin descripcion registrada.' }}</p></section>
    <section class="section-wrap">
        <div class="admin-detail">
            <dl>
                <div><dt>Slug</dt><dd><code>{{ $category->slug }}</code></dd></div>
                <div><dt>Productos asociados</dt><dd>{{ $category->products_count }}</dd></div>
                <div><dt>Estado</dt><dd>{{ $category->is_active ? 'Activa' : 'Inactiva' }}</dd></div>
            </dl>
            <div class="form-actions"><a class="button primary" href="{{ route('admin.categories.edit', $category) }}">Editar categoria</a><a class="button ghost" href="{{ route('admin.categories.index') }}">Volver</a></div>
        </div>
    </section>
@endsection
