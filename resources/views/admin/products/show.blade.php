@extends('layouts.app')

@section('title', $product->name . ' | Administracion')

@section('content')
    <section class="section-wrap admin-product-detail">
        <div class="detail-media"><img src="{{ $product->imageUrl() }}" alt="Imagen de {{ $product->name }}"></div>
        <div class="admin-detail">
            <p class="eyebrow">{{ $product->category->name }}</p>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>
            <dl>
                <div><dt>Slug</dt><dd><code>{{ $product->slug }}</code></dd></div>
                <div><dt>Precio</dt><dd>${{ number_format((float) $product->price, 2) }} MXN</dd></div>
                <div><dt>Existencias</dt><dd>{{ $product->stock }}</dd></div>
                <div><dt>Estado</dt><dd>{{ $product->is_active ? 'Activo' : 'Inactivo' }}</dd></div>
            </dl>
            <div class="form-actions"><a class="button primary" href="{{ route('admin.products.edit', $product) }}">Editar producto</a><a class="button ghost" href="{{ route('admin.products.index') }}">Volver</a></div>
        </div>
    </section>
@endsection
