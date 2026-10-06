<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Support\UniqueSlug;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request, UniqueSlug $slugs): RedirectResponse
    {
        $data = $request->validated();
        $category = Category::create([
            'name' => $data['name'],
            'slug' => $slugs->generate(Category::class, $data['name'], 140),
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.show', $category)
            ->with('status', 'Categoria creada correctamente.');
    }

    public function show(Category $category): View
    {
        $category->loadCount('products');

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.show', $category)
            ->with('status', 'Categoria actualizada correctamente.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors([
                'category' => 'No se puede eliminar una categoria con productos asociados.',
            ]);
        }

        try {
            $category->delete();
        } catch (QueryException) {
            return back()->withErrors([
                'category' => 'No se puede eliminar una categoria con productos asociados.',
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('status', 'Categoria eliminada correctamente.');
    }
}
