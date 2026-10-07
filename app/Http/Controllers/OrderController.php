<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Exceptions\CheckoutAlreadyProcessedException;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    public function create(CartService $cart): View|RedirectResponse
    {
        $contents = $cart->contents();

        if ($contents['is_empty']) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Tu carrito esta vacio. Agrega productos antes de confirmar un pedido.',
            ]);
        }

        if (! $contents['is_valid']) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Corrige los productos no disponibles antes de confirmar un pedido.',
            ]);
        }

        return view('orders.checkout', [
            'cart' => $contents,
            'confirmationKey' => $cart->confirmationKey(),
        ]);
    }

    public function store(Request $request, CartService $cart, CheckoutService $checkout): RedirectResponse
    {
        $data = $request->validate([
            'confirmation_key' => ['required', 'string', 'size:64'],
        ]);

        if (! $cart->hasConfirmationKey($data['confirmation_key'])) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'La confirmacion no es valida. Revisa tu carrito e intentalo de nuevo.',
            ]);
        }

        try {
            $order = $checkout->place($request->user(), $cart->rawItems(), $data['confirmation_key']);
        } catch (CartException|CheckoutAlreadyProcessedException $exception) {
            return redirect()->route('cart.index')->withErrors(['cart' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('cart.index')->withErrors([
                'cart' => 'No se pudo confirmar el pedido. Tu carrito se conserva para que puedas intentarlo de nuevo.',
            ]);
        }

        $cart->clear();

        return redirect()->route('orders.show', $order)->with('status', 'Pedido ficticio confirmado. No se realizo ningun cobro.');
    }

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->withCount('items')
            ->orderByDesc('ordered_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
