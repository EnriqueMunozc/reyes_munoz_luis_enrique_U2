<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        return view('cart.index', ['cart' => $cart->contents()]);
    }

    public function store(Request $request, Product $product, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:2147483647'],
        ]);

        try {
            $cart->add($product, $data['quantity']);
        } catch (CartException $exception) {
            return back()->withErrors(['quantity' => $exception->getMessage()]);
        }

        return back()->with('status', 'Producto agregado al carrito.');
    }

    public function update(Request $request, int $product, CartService $cart): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:2147483647'],
        ]);

        try {
            $cart->update($product, $data['quantity']);
        } catch (CartException $exception) {
            return redirect()->route('cart.index')->withErrors(['cart' => $exception->getMessage()]);
        }

        return redirect()->route('cart.index')->with('status', 'Cantidad actualizada.');
    }

    public function destroy(int $product, CartService $cart): RedirectResponse
    {
        $cart->remove($product);

        return redirect()->route('cart.index')->with('status', 'Producto retirado del carrito.');
    }

    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return redirect()->route('cart.index')->with('status', 'Carrito vaciado.');
    }
}
