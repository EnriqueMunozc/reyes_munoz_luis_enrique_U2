@extends('layouts.app')

@section('title', 'Confirmar pedido | Grand Line Store')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Pedido ficticio</p>
        <h1>Confirma tu pedido</h1>
        <p>Esta es una simulacion escolar. No se procesan pagos ni datos bancarios.</p>
    </section>

    <section class="section-wrap checkout-layout">
        <div class="checkout-list">
            @foreach ($cart['lines'] as $line)
                <div class="checkout-line">
                    <span>{{ $line['quantity'] }} x {{ $line['product']->name }}</span>
                    <strong>{{ \App\Support\Money::format($line['subtotal_cents']) }}</strong>
                </div>
            @endforeach
        </div>

        <aside class="cart-summary">
            <p class="eyebrow">Total a registrar</p>
            <strong class="checkout-total">{{ \App\Support\Money::format($cart['total_cents']) }}</strong>
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="confirmation_key" value="{{ $confirmationKey }}">
                <button class="button primary" type="submit">Confirmar pedido ficticio</button>
            </form>
            <a class="text-link" href="{{ route('cart.index') }}">Volver al carrito</a>
        </aside>
    </section>
@endsection
