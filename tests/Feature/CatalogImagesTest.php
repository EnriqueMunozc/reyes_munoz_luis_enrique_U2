<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductImageManager;
use Database\Seeders\StoreCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogImagesTest extends TestCase
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

    public function test_public_catalog_images_render_in_home_catalog_detail_and_cart(): void
    {
        $category = Category::create([
            'name' => 'Figuras',
            'slug' => 'figuras',
            'description' => 'Figuras de prueba.',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Figura Capitan del Sombrero de Paja',
            'slug' => 'figura-capitan-sombrero-paja',
            'description' => 'Producto de prueba.',
            'price' => '899.00',
            'stock' => 10,
            'image_path' => 'images/products/luffy-figura.jpg',
            'is_active' => true,
            'is_featured' => true,
        ]);

        $expectedUrl = asset('images/products/luffy-figura.jpg');

        $this->get(route('home'))->assertOk()->assertSee($expectedUrl);
        $this->get(route('catalog.index'))->assertOk()->assertSee($expectedUrl);
        $this->get(route('catalog.show', $product))->assertOk()->assertSee($expectedUrl);
        $this->withSession(['grand_line.cart.items' => [$product->id => 1]])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee($expectedUrl);
    }

    public function test_catalog_images_are_not_managed_uploads_and_seeder_assigns_them(): void
    {
        $path = 'images/products/luffy-figura.jpg';
        $images = app(ProductImageManager::class);

        $this->assertTrue($images->isCatalogImagePath($path));
        $this->assertFalse($images->isManagedPath($path));
        $images->deleteIfUnused($path);
        $this->assertFileExists(public_path($path));

        $this->seed(StoreCatalogSeeder::class);

        $this->assertDatabaseCount('products', 12);
        $this->assertDatabaseHas('products', [
            'slug' => 'figura-capitan-sombrero-paja',
            'image_path' => $path,
        ]);
        $this->assertDatabaseHas('products', [
            'slug' => 'poster-tripulacion-atardecer',
            'image_path' => 'images/products/poster al atardecer.jpg',
        ]);
    }
}
