<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Models\Product;
use App\Support\Money;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartService
{
    private const SESSION_KEY = 'grand_line.cart.items';

    private const CHECKOUT_KEY = 'grand_line.cart.checkout_key';

    /**
     * @return array<int, int>
     */
    public function rawItems(): array
    {
        $items = Session::get(self::SESSION_KEY, []);

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn ($quantity, $productId) => ctype_digit((string) $productId) && is_int($quantity) && $quantity > 0)
            ->mapWithKeys(fn (int $quantity, $productId) => [(int) $productId => $quantity])
            ->all();
    }

    public function count(): int
    {
        return array_sum($this->rawItems());
    }

    public function add(Product $product, int $quantity): void
    {
        if ($quantity < 1) {
            throw new CartException('La cantidad debe ser un numero entero positivo.');
        }

        $items = $this->rawItems();
        $requestedQuantity = ($items[$product->id] ?? 0) + $quantity;

        $this->assertAvailable($product, $requestedQuantity);

        $items[$product->id] = $requestedQuantity;
        $this->putItems($items);
    }

    public function update(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            throw new CartException('La cantidad debe ser un numero entero positivo.');
        }

        $product = Product::query()->find($productId);

        if ($product === null) {
            $this->remove($productId);

            throw new CartException('El producto ya no existe y fue retirado del carrito.');
        }

        $this->assertAvailable($product, $quantity);
        $items = $this->rawItems();
        $items[$productId] = $quantity;
        $this->putItems($items);
    }

    public function remove(int $productId): void
    {
        $items = $this->rawItems();
        unset($items[$productId]);
        $this->putItems($items);
    }

    public function clear(): void
    {
        Session::forget([self::SESSION_KEY, self::CHECKOUT_KEY]);
    }

    public function forgetCheckoutKey(): void
    {
        Session::forget(self::CHECKOUT_KEY);
    }

    public function confirmationKey(): string
    {
        $key = Session::get(self::CHECKOUT_KEY);

        if (! is_string($key) || strlen($key) !== 64) {
            $key = Str::random(64);
            Session::put(self::CHECKOUT_KEY, $key);
        }

        return $key;
    }

    public function hasConfirmationKey(string $key): bool
    {
        $storedKey = Session::get(self::CHECKOUT_KEY);

        return is_string($storedKey) && hash_equals($storedKey, $key);
    }

    /**
     * @return array{lines: array<int, array<string, mixed>>, count: int, total_cents: int, is_empty: bool, is_valid: bool}
     */
    public function contents(): array
    {
        $items = $this->rawItems();
        $products = Product::query()->whereIn('id', array_keys($items))->get()->keyBy('id');
        $lines = [];
        $totalCents = 0;
        $isValid = $items !== [];

        foreach ($items as $productId => $quantity) {
            $product = $products->get($productId);
            $message = null;

            if ($product === null) {
                $message = 'Este producto ya no existe. Retiralo del carrito para continuar.';
            } elseif (! $product->is_active) {
                $message = 'Este producto ya no esta disponible. Retiralo del carrito para continuar.';
            } elseif ($product->stock < 1) {
                $message = 'Este producto se agoto. Retiralo del carrito para continuar.';
            } elseif ($quantity > $product->stock) {
                $message = 'La cantidad seleccionada supera las existencias disponibles.';
            }

            $unitCents = $product === null ? 0 : Money::toCents($product->price);
            $subtotalCents = $unitCents * $quantity;

            if ($message !== null) {
                $isValid = false;
            } else {
                $totalCents += $subtotalCents;
            }

            $lines[] = [
                'product_id' => $productId,
                'product' => $product,
                'quantity' => $quantity,
                'unit_cents' => $unitCents,
                'subtotal_cents' => $subtotalCents,
                'message' => $message,
            ];
        }

        return [
            'lines' => $lines,
            'count' => array_sum($items),
            'total_cents' => $totalCents,
            'is_empty' => $items === [],
            'is_valid' => $isValid,
        ];
    }

    private function assertAvailable(Product $product, int $quantity): void
    {
        if (! $product->is_active) {
            throw new CartException('Este producto ya no esta disponible.');
        }

        if ($product->stock < 1) {
            throw new CartException('Este producto esta agotado.');
        }

        if ($quantity > $product->stock) {
            throw new CartException('La cantidad solicitada supera las existencias disponibles.');
        }
    }

    /**
     * @param  array<int, int>  $items
     */
    private function putItems(array $items): void
    {
        if ($items === []) {
            $this->clear();

            return;
        }

        Session::put(self::SESSION_KEY, $items);
    }
}
