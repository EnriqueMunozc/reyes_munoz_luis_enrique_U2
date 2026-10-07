@extends('layouts.app')

@section('title', 'Pedido ' . $order->folio . ' | Grand Line Store')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Pedido ficticio {{ $order->folio }}</p>
        <h1>Detalle del pedido</h1>
        <p>Confirmado el {{ $order->ordered_at->format('d/m/Y H:i') }}. No se realizo ningun cobro.</p>
    </section>

    <section class="section-wrap order-detail">
        <div class="checkout-list">
            @foreach ($order->items as $item)
                <div class="checkout-line">
                    <span>{{ $item->quantity }} x {{ $item->product_name }}<small>${{ number_format((float) $item->unit_price, 2) }} MXN por unidad</small></span>
                    <strong>${{ number_format((float) $item->subtotal, 2) }} MXN</strong>
                </div>
            @endforeach
        </div>
        <aside class="cart-summary">
            <p class="eyebrow">Total registrado</p>
            <strong class="checkout-total">${{ number_format((float) $order->total, 2) }} MXN</strong>
            <span class="status-badge">{{ ucfirst($order->status) }}</span>
            <a class="button ghost" href="{{ route('orders.index') }}">Volver a mis pedidos</a>
        </aside>
    </section>
@endsection
