<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductImageManager;
use App\Support\UniqueSlug;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('buscar', ''));
        $categoryId = (string) $request->query('categoria', '');

        $products = Product::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categoryId !== '', fn ($query) => $query->where('category_id', $categoryId))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request, UniqueSlug $slugs, ProductImageManager $images): RedirectResponse
    {
        $data = $this->productData($request);
        $newImagePath = null;

        try {
            if ($request->hasFile('image')) {
                $newImagePath = $images->store($request->file('image'));
                $data['image_path'] = $newImagePath;
            }

            $product = DB::transaction(function () use ($data, $slugs) {
                $data['slug'] = $slugs->generate(Product::class, $data['name'], 180);

                return Product::create($data);
            });
        } catch (Throwable $exception) {
            $images->deleteIfUnused($newImagePath);
            report($exception);

            return back()->withInput()->withErrors([
                'image' => 'No se pudo guardar el producto ni su imagen. Intentalo de nuevo.',
            ]);
        }

        return redirect()->route('admin.products.show', $product)
            ->with('status', 'Producto creado correctamente.');
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $product->load('category');
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product, ProductImageManager $images): RedirectResponse
    {
        $data = $this->productData($request);
        $newImagePath = null;
        $previousImagePath = $product->image_path;

        try {
            if ($request->hasFile('image')) {
                $newImagePath = $images->store($request->file('image'));
                $data['image_path'] = $newImagePath;
            }

            DB::transaction(function () use ($product, $data) {
                $product->update($data);
            });
        } catch (Throwable $exception) {
            $images->deleteIfUnused($newImagePath);
            report($exception);

            return back()->withInput()->withErrors([
                'image' => 'No se pudo actualizar el producto ni su imagen. Intentalo de nuevo.',
            ]);
        }

        if ($newImagePath !== null && $newImagePath !== $previousImagePath) {
            $images->deleteIfUnused($previousImagePath);
        }

        return redirect()->route('admin.products.show', $product)
            ->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product, ProductImageManager $images): RedirectResponse
    {
        $imagePath = $product->image_path;

        DB::transaction(function () use ($product) {
            $product->delete();
        });

        $images->deleteIfUnused($imagePath);

        return redirect()->route('admin.products.index')
            ->with('status', 'Producto eliminado correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function productData(ProductRequest $request): array
    {
        $data = $request->validated();

        return [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }
}
