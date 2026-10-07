@extends('layouts.app')

@section('title', 'Mis pedidos | Grand Line Store')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Bitacora de compra</p>
        <h1>Mis pedidos</h1>
        <p>Historial de tus pedidos ficticios confirmados.</p>
    </section>

    <section class="section-wrap">
        <div class="order-list">
            @forelse ($orders as $order)
                <article class="order-card">
                    <div>
                        <p class="eyebrow">{{ $order->folio }}</p>
                        <h2>Pedido {{ $order->status }}</h2>
                        <p>{{ $order->ordered_at->format('d/m/Y H:i') }} | {{ $order->items_count }} articulos</p>
                    </div>
                    <div class="order-card-total">
                        <strong>${{ number_format((float) $order->total, 2) }} MXN</strong>
                        <a class="text-link" href="{{ route('orders.show', $order) }}">Ver detalle</a>
                    </div>
                </article>
            @empty
                <div class="cart-empty">
                    <h2>Aun no tienes pedidos</h2>
                    <p>Cuando confirmes una compra ficticia aparecera aqui.</p>
                    <a class="button primary" href="{{ route('catalog.index') }}">Ver catalogo</a>
                </div>
            @endforelse
        </div>

        <div class="pagination-wrap">{{ $orders->links() }}</div>
    </section>
@endsection
