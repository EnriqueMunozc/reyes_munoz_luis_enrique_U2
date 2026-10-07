<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAndOrdersTest extends TestCase
{
    use RefreshDatabase {
        refreshTestDatabase as private runRefreshTestDatabase;
    }

    protected function refreshTestDatabase(): void
    {
        $this->assertSame('sqlsrv', config('database.default'));
        $this->assertSame('redline_test', config('database.connections.sqlsrv.database'));

        $this->runRefreshTestDatabase();
    }

    public function test_guest_can_add_accumulate_update_remove_and_clear_cart_items(): void
    {
        $product = $this->product(['stock' => 6]);

        $this->post(route('cart.store', $product), ['quantity' => 2])->assertSessionHas('status');
        $this->post(route('cart.store', $product), ['quantity' => 3])->assertSessionHas('status');
        $this->get(route('cart.index'))->assertOk()->assertSee('5')->assertSee($product->name);

        $this->patch(route('cart.update', $product->id), ['quantity' => 7])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHasErrors('cart');
        $this->get(route('cart.index'))->assertSessionHas('grand_line.cart.items.'.$product->id, 5);

        $this->patch(route('cart.update', $product->id), ['quantity' => 4])
            ->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertSessionHas('grand_line.cart.items.'.$product->id, 4);

        $this->delete(route('cart.destroy', $product->id))->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertSessionMissing('grand_line.cart.items.'.$product->id);

        $this->post(route('cart.store', $product), ['quantity' => 1]);
        $this->delete(route('cart.clear'))->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertSessionMissing('grand_line.cart.items');
    }

    public function test_cart_rejects_invalid_and_accumulated_quantities_above_stock(): void
    {
        $product = $this->product(['stock' => 3]);

        $this->from(route('catalog.show', $product))
            ->post(route('cart.store', $product), ['quantity' => 0])
            ->assertRedirect(route('catalog.show', $product))
            ->assertSessionHasErrors('quantity');

        $this->post(route('cart.store', $product), ['quantity' => 2])->assertSessionHas('status');
        $this->post(route('cart.store', $product), ['quantity' => 2])->assertSessionHasErrors('quantity');
        $this->get(route('cart.index'))->assertSessionHas('grand_line.cart.items.'.$product->id, 2);
    }

    public function test_cart_marks_inactive_out_of_stock_and_deleted_products_for_correction(): void
    {
        $inactive = $this->product(['name' => 'Inactivo', 'slug' => 'inactivo', 'is_active' => false]);
        $outOfStock = $this->product(['name' => 'Agotado', 'slug' => 'agotado', 'stock' => 0]);
        $deleted = $this->product(['name' => 'Eliminado', 'slug' => 'eliminado']);
        $deletedId = $deleted->id;
        $deleted->delete();

        $this->withSession([
            'grand_line.cart.items' => [$inactive->id => 1, $outOfStock->id => 1, $deletedId => 1],
        ])->get(route('cart.index'))
            ->assertOk()
            ->assertSee('ya no esta disponible')
            ->assertSee('se agoto')
            ->assertSee('ya no existe');
    }

    public function test_cart_is_preserved_on_login_and_cleared_before_logout(): void
    {
        $product = $this->product();
        $user = User::factory()->create(['password' => bcrypt('contrasena-segura')]);

        $this->post(route('cart.store', $product), ['quantity' => 2]);
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'contrasena-segura',
        ])->assertRedirect(route('catalog.index'));

        $this->get(route('cart.index'))->assertSessionHas('grand_line.cart.items.'.$product->id, 2);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->get(route('cart.index'))->assertOk()->assertSee('Tu carrito esta vacio');
    }

    public function test_checkout_requires_authentication_and_rejects_empty_cart(): void
    {
        $this->get(route('orders.create'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('orders.create'))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHasErrors('cart');
    }

    public function test_authenticated_user_can_view_checkout_confirmation(): void
    {
        $product = $this->product();

        $this->actingAs(User::factory()->create())
            ->withSession(['grand_line.cart.items' => [$product->id => 1]])
            ->get(route('orders.create'))
            ->assertOk()
            ->assertSee('Confirma tu pedido')
            ->assertSee($product->name)
            ->assertSee('Pedido ficticio');
    }

    public function test_checkout_uses_database_prices_creates_history_and_decrements_stock_once(): void
    {
        $first = $this->product(['name' => 'Mapa', 'slug' => 'mapa', 'price' => '199.90', 'stock' => 5]);
        $second = $this->product(['name' => 'Brjula', 'slug' => 'brjula', 'price' => '50.05', 'stock' => 4]);
        $key = str_repeat('a', 64);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession([
                'grand_line.cart.items' => [$first->id => 2, $second->id => 3],
                'grand_line.cart.checkout_key' => $key,
            ])
            ->post(route('orders.store'), [
                'confirmation_key' => $key,
                'total' => '0.01',
                'price' => '0.01',
            ])
            ->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertSame('549.95', $order->total);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_name' => 'Mapa',
            'unit_price' => '199.90',
            'quantity' => 2,
            'subtotal' => '399.80',
        ]);
        $this->assertDatabaseHas('products', ['id' => $first->id, 'stock' => 3]);
        $this->assertDatabaseHas('products', ['id' => $second->id, 'stock' => 1]);
        $this->get(route('cart.index'))->assertSessionMissing('grand_line.cart.items');

        $this->post(route('orders.store'), ['confirmation_key' => $key])
            ->assertRedirect(route('cart.index'));
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('products', ['id' => $first->id, 'stock' => 3]);
    }

    public function test_checkout_revalidates_availability_without_partial_order_or_stock_changes(): void
    {
        $first = $this->product(['name' => 'Primer producto', 'slug' => 'primer-producto', 'stock' => 5]);
        $second = $this->product(['name' => 'Segundo producto', 'slug' => 'segundo-producto', 'stock' => 1]);
        $key = str_repeat('b', 64);

        $this->actingAs(User::factory()->create())
            ->withSession([
                'grand_line.cart.items' => [$first->id => 2, $second->id => 2],
                'grand_line.cart.checkout_key' => $key,
            ])
            ->post(route('orders.store'), ['confirmation_key' => $key])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('products', ['id' => $first->id, 'stock' => 5]);
        $this->assertDatabaseHas('products', ['id' => $second->id, 'stock' => 1]);
        $this->get(route('cart.index'))->assertSessionHas('grand_line.cart.items.'.$first->id, 2);
    }

    public function test_user_can_only_view_own_history_and_order_details_survive_product_deletion(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = $this->product();
        $order = Order::create([
            'user_id' => $owner->id,
            'folio' => 'GLS-20261006-HISTORY1234',
            'confirmation_key' => str_repeat('c', 64),
            'total' => '199.00',
            'status' => 'confirmado',
            'ordered_at' => now(),
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => '199.00',
            'quantity' => 1,
            'subtotal' => '199.00',
        ]);

        $this->actingAs($other)->get(route('orders.show', $order))->assertNotFound();
        $this->actingAs($owner)->get(route('orders.index'))->assertOk()->assertSee($order->folio);
        $this->actingAs($owner)->get(route('orders.show', $order))->assertOk()->assertSee($product->name);

        $product->delete();

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => $product->name,
            'subtotal' => '199.00',
        ]);
        $this->actingAs($owner)->get(route('orders.show', $order))->assertOk()->assertSee($product->name);
    }

    private function product(array $attributes = []): Product
    {
        $category = Category::first() ?? Category::create([
            'name' => 'Accesorios',
            'slug' => 'accesorios',
            'description' => 'Categoria de prueba.',
            'is_active' => true,
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Producto '.(Product::count() + 1),
            'slug' => 'producto-'.(Product::count() + 1),
            'description' => 'Producto de prueba para el carrito.',
            'price' => '199.00',
            'stock' => 10,
            'image_path' => null,
            'is_active' => true,
            'is_featured' => false,
        ], $attributes));
    }
}
