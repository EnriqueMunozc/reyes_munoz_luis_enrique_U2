<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home(): View
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('name')
            ->take(6)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('catalog.home', compact('featuredProducts', 'categories'));
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('buscar', ''));
        $categorySlug = (string) $request->query('categoria', '');

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug) {
                $query->whereHas('category', fn ($category) => $category->where('slug', $categorySlug));
            })
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('catalog.index', compact('products', 'categories', 'search', 'categorySlug'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('category');

        $relatedProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->orderBy('name')
            ->take(3)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}
