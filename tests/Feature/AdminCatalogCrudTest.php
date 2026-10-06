<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCatalogCrudTest extends TestCase
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

    public function test_administrator_can_create_and_update_category_while_preserving_slug(): void
    {
        $admin = $this->administrator();

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Barcos especiales',
                'description' => 'Modelos para navegar la Grand Line.',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $category = Category::firstOrFail();
        $this->assertSame('barcos-especiales', $category->slug);

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Barcos especiales',
                'description' => 'Otra categoria con el mismo nombre.',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['slug' => 'barcos-especiales-2']);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Barcos legendarios',
                'description' => 'Modelos actualizados.',
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.categories.show', $category));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Barcos legendarios',
            'slug' => 'barcos-especiales',
            'is_active' => false,
        ]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = $this->category();
        $this->product($category);

        $this->actingAs($this->administrator())
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
        $this->assertDatabaseCount('products', 1);
    }

    public function test_administrator_can_create_product_with_managed_image_and_preserved_slug_on_edit(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $admin = $this->administrator();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), $this->productPayload($category, [
                'name' => 'Brujula del rey',
                'image' => UploadedFile::fake()->image('brujula.png', 640, 480),
            ]))
            ->assertRedirect();

        $product = Product::firstOrFail();
        $this->assertSame('brujula-del-rey', $product->slug);
        Storage::disk('public')->assertExists($product->image_path);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), $this->productPayload($category, [
                'name' => 'Brujula del nuevo mundo',
                'is_active' => '0',
            ]))
            ->assertRedirect(route('admin.products.show', $product));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Brujula del nuevo mundo',
            'slug' => 'brujula-del-rey',
            'is_active' => false,
        ]);
    }

    public function test_product_validation_rejects_invalid_values_and_non_image_content(): void
    {
        $category = $this->category();

        $this->actingAs($this->administrator())
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $this->productPayload($category, [
                'category_id' => 999999,
                'price' => '-1.001',
                'stock' => '-1',
                'image' => UploadedFile::fake()->create('falsa.jpg', 20, 'text/plain'),
            ]))
            ->assertRedirect(route('admin.products.create'))
            ->assertSessionHasErrors(['category_id', 'price', 'stock', 'image']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_replacing_and_deleting_product_images_only_removes_unshared_managed_files(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $oldPath = 'products/imagen-anterior.jpg';
        Storage::disk('public')->put($oldPath, 'imagen');
        $product = $this->product($category, ['image_path' => $oldPath]);

        $this->actingAs($this->administrator())
            ->put(route('admin.products.update', $product), $this->productPayload($category, [
                'image' => UploadedFile::fake()->image('reemplazo.jpg'),
            ]))
            ->assertRedirect();

        Storage::disk('public')->assertMissing($oldPath);
        $product->refresh();
        Storage::disk('public')->assertExists($product->image_path);

        $sharedPath = 'products/compartida.jpg';
        Storage::disk('public')->put($sharedPath, 'imagen compartida');
        $first = $this->product($category, ['image_path' => $sharedPath, 'slug' => 'producto-compartido-uno']);
        $second = $this->product($category, ['image_path' => $sharedPath, 'slug' => 'producto-compartido-dos']);

        $this->actingAs($this->administrator())->delete(route('admin.products.destroy', $first))->assertRedirect();
        Storage::disk('public')->assertExists($sharedPath);

        $this->actingAs($this->administrator())->delete(route('admin.products.destroy', $second))->assertRedirect();
        Storage::disk('public')->assertMissing($sharedPath);
    }

    public function test_inactive_product_is_hidden_from_public_detail_and_admin_list_can_filter(): void
    {
        $category = $this->category(['name' => 'Figuras', 'slug' => 'figuras']);
        $inactive = $this->product($category, [
            'name' => 'Producto inactivo',
            'is_active' => false,
            'slug' => 'producto-inactivo',
        ]);
        $active = $this->product($category, ['name' => 'Producto activo', 'slug' => 'producto-activo']);

        $this->get(route('catalog.show', $inactive))->assertNotFound();

        $this->actingAs($this->administrator())
            ->get(route('admin.products.index', ['buscar' => 'activo', 'categoria' => $category->id]))
            ->assertOk()
            ->assertSee($active->name)
            ->assertSee($inactive->name);
    }

    public function test_administrator_can_view_category_and_product_management_pages(): void
    {
        $category = $this->category();
        $product = $this->product($category);
        $this->actingAs($this->administrator());

        $this->get(route('admin.categories.index'))->assertOk();
        $this->get(route('admin.categories.create'))->assertOk();
        $this->get(route('admin.categories.show', $category))->assertOk();
        $this->get(route('admin.categories.edit', $category))->assertOk();
        $this->get(route('admin.products.index'))->assertOk()->assertSee('Eliminar este producto?');
        $this->get(route('admin.products.create'))->assertOk();
        $this->get(route('admin.products.show', $product))->assertOk();
        $this->get(route('admin.products.edit', $product))->assertOk();
    }

    private function administrator(): User
    {
        return User::factory()->create(['role' => UserRole::Administrator]);
    }

    private function category(array $attributes = []): Category
    {
        return Category::create(array_merge([
            'name' => 'Accesorios',
            'slug' => 'accesorios',
            'description' => 'Accesorios de prueba.',
            'is_active' => true,
        ], $attributes));
    }

    private function product(Category $category, array $attributes = []): Product
    {
        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Producto de prueba',
            'slug' => 'producto-de-prueba-'.(Product::count() + 1),
            'description' => 'Descripcion de prueba.',
            'price' => 199.00,
            'stock' => 10,
            'image_path' => null,
            'is_active' => true,
            'is_featured' => false,
        ], $attributes));
    }

    /**
     * @return array<string, mixed>
     */
    private function productPayload(Category $category, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $category->id,
            'name' => 'Producto administrado',
            'description' => 'Descripcion para el producto administrado.',
            'price' => '299.90',
            'stock' => '8',
            'is_active' => '1',
            'is_featured' => '0',
        ], $overrides);
    }
}
