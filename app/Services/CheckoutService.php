<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Exceptions\CheckoutAlreadyProcessedException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Support\Money;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    private const MAX_TOTAL_CENTS = 999999999999;

    /**
     * @param  array<int, int>  $items
     */
    public function place(User $user, array $items, string $confirmationKey): Order
    {
        if ($items === []) {
            throw new CartException('Tu carrito esta vacio. Agrega productos antes de confirmar un pedido.');
        }

        try {
            return DB::transaction(function () use ($user, $items, $confirmationKey) {
                if (Order::query()->where('confirmation_key', $confirmationKey)->exists()) {
                    throw new CheckoutAlreadyProcessedException('Esta confirmacion ya fue procesada.');
                }

                $products = Product::query()
                    ->whereIn('id', array_keys($items))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $lines = [];
                $totalCents = 0;

                foreach ($items as $productId => $quantity) {
                    $product = $products->get($productId);

                    if ($product === null) {
                        throw new CartException('Uno de los productos ya no existe. Corrige tu carrito e intentalo de nuevo.');
                    }

                    if (! $product->is_active) {
                        throw new CartException("{$product->name} ya no esta disponible. Corrige tu carrito e intentalo de nuevo.");
                    }

                    if ($product->stock < $quantity) {
                        throw new CartException("No hay existencias suficientes para {$product->name}. Corrige tu carrito e intentalo de nuevo.");
                    }

                    $unitCents = Money::toCents($product->price);
                    $subtotalCents = $unitCents * $quantity;
                    $totalCents += $subtotalCents;

                    if ($subtotalCents > self::MAX_TOTAL_CENTS || $totalCents > self::MAX_TOTAL_CENTS) {
                        throw new CartException('El importe del pedido supera el limite permitido.');
                    }

                    $lines[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'unit_cents' => $unitCents,
                        'subtotal_cents' => $subtotalCents,
                    ];
                }

                $order = Order::query()->create([
                    'user_id' => $user->id,
                    'folio' => $this->folio(),
                    'confirmation_key' => $confirmationKey,
                    'total' => Money::fromCents($totalCents),
                    'status' => 'confirmado',
                    'ordered_at' => now(),
                ]);

                foreach ($lines as $line) {
                    /** @var Product $product */
                    $product = $line['product'];
                    $updated = Product::query()
                        ->whereKey($product->id)
                        ->where('is_active', true)
                        ->where('stock', '>=', $line['quantity'])
                        ->decrement('stock', $line['quantity']);

                    if ($updated !== 1) {
                        throw new CartException("Las existencias de {$product->name} cambiaron. Corrige tu carrito e intentalo de nuevo.");
                    }

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => Money::fromCents($line['unit_cents']),
                        'quantity' => $line['quantity'],
                        'subtotal' => Money::fromCents($line['subtotal_cents']),
                    ]);
                }

                return $order;
            }, 3);
        } catch (QueryException $exception) {
            if (Order::query()->where('confirmation_key', $confirmationKey)->exists()) {
                throw new CheckoutAlreadyProcessedException('Esta confirmacion ya fue procesada.', previous: $exception);
            }

            throw $exception;
        }
    }

    private function folio(): string
    {
        do {
            $folio = 'GLS-'.now()->format('Ymd').'-'.Str::upper(Str::random(12));
        } while (Order::query()->where('folio', $folio)->exists());

        return $folio;
    }
}
