@extends('layouts.app')

@section('title', 'Carrito | Grand Line Store')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Preparar el viaje</p>
        <h1>Tu carrito</h1>
        <p>Los precios y las existencias se validan nuevamente al confirmar el pedido ficticio.</p>
    </section>

    <section class="section-wrap cart-layout">
        @if ($cart['is_empty'])
            <div class="cart-empty">
                <h2>Tu carrito esta vacio</h2>
                <p>Explora el catalogo para agregar productos a tu tripulacion.</p>
                <a class="button primary" href="{{ route('catalog.index') }}">Ver catalogo</a>
            </div>
        @else
            <div class="cart-items">
                @foreach ($cart['lines'] as $line)
                    @php($product = $line['product'])
                    <article class="cart-line @if ($line['message']) cart-line-invalid @endif">
                        @if ($product)
                            <img src="{{ $product->imageUrl() }}" alt="Imagen de {{ $product->name }}">
                        @else
                            <div class="cart-missing-image" aria-hidden="true">GL</div>
                        @endif

                        <div class="cart-line-copy">
                            <h2>{{ $product?->name ?? 'Producto no disponible' }}</h2>
                            @if ($product)
                                <p>{{ \App\Support\Money::format($line['unit_cents']) }} por unidad</p>
                            @endif
                            @if ($line['message'])
                                <p class="cart-warning">{{ $line['message'] }}</p>
                            @endif
                        </div>

                        <form class="quantity-form" action="{{ route('cart.update', $line['product_id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label for="quantity-{{ $line['product_id'] }}">Cantidad</label>
                            <input id="quantity-{{ $line['product_id'] }}" name="quantity" type="number" min="1" @if ($product) max="{{ $product->stock }}" @endif value="{{ $line['quantity'] }}" required>
                            <button type="submit">Actualizar</button>
                        </form>

                        <div class="cart-line-total">
                            @if (! $line['message'])
                                <strong>{{ \App\Support\Money::format($line['subtotal_cents']) }}</strong>
                            @endif
                            <form action="{{ route('cart.destroy', $line['product_id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Retirar</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="cart-summary">
                <p class="eyebrow">Resumen</p>
                <dl>
                    <div><dt>Articulos</dt><dd>{{ $cart['count'] }}</dd></div>
                    <div><dt>Total</dt><dd>{{ \App\Support\Money::format($cart['total_cents']) }}</dd></div>
                </dl>
                <p class="simulation-note">Pedido ficticio: no solicita datos bancarios ni realiza cargos.</p>

                @if ($cart['is_valid'])
                    <a class="button primary" href="{{ route('orders.create') }}">Continuar a confirmacion</a>
                @else
                    <p class="cart-warning">Corrige o retira los productos marcados antes de continuar.</p>
                @endif

                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="button ghost" type="submit">Vaciar carrito</button>
                </form>
            </aside>
        @endif
    </section>
@endsection
