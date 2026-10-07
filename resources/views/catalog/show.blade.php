@extends('layouts.app')

@section('title', $product->name . ' | Grand Line Store')

@section('content')
    <section class="product-detail">
        <div class="detail-media">
            <img src="{{ $product->imageUrl() }}" alt="Imagen de {{ $product->name }}">
        </div>

        <div class="detail-copy">
            <a class="breadcrumb" href="{{ route('catalog.index') }}">Catalogo</a>
            <p class="eyebrow">{{ $product->category->name }}</p>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>

            <dl class="detail-list">
                <div>
                    <dt>Precio</dt>
                    <dd>${{ number_format((float) $product->price, 2) }} MXN</dd>
                </div>
                <div>
                    <dt>Existencias</dt>
                    <dd>{{ $product->stock }} unidades</dd>
                </div>
                <div>
                    <dt>Estado</dt>
                    <dd>{{ $product->is_active ? 'Activo' : 'No disponible' }}</dd>
                </div>
            </dl>

            @if ($product->stock > 0)
                <form class="add-to-cart-form" action="{{ route('cart.store', $product) }}" method="POST">
                    @csrf
                    <label for="quantity">Cantidad</label>
                    <input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" required>
                    <button class="button primary" type="submit">Agregar al carrito</button>
                </form>
            @else
                <p class="availability-note">Este producto esta agotado por ahora.</p>
            @endif
        </div>
    </section>

    <section class="section-wrap">
        <div class="section-heading">
            <p class="eyebrow">Misma categoria</p>
            <h2>Tambien podria interesarte</h2>
        </div>

        <div class="product-grid compact-grid">
            @forelse ($relatedProducts as $relatedProduct)
                <x-product-card :product="$relatedProduct" />
            @empty
                <p class="empty-state">No hay productos relacionados por ahora.</p>
            @endforelse
        </div>
    </section>
@endsection
