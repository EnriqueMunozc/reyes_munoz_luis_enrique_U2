@extends('layouts.app')

@section('title', 'Catalogo | Grand Line Store')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Catalogo</p>
        <h1>Productos disponibles</h1>
        <p>Busca por nombre o descripcion y filtra por categoria. Todos los resultados se consultan desde la base de datos configurada.</p>
    </section>

    <section class="catalog-layout">
        <aside class="filters-panel">
            <form action="{{ route('catalog.index') }}" method="GET">
                <label for="buscar">Buscar</label>
                <input id="buscar" name="buscar" type="search" value="{{ $search }}" placeholder="Figura, manga, gorra">

                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($categorySlug === $category->slug)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <div class="filter-actions">
                    <button class="button primary" type="submit">Aplicar filtros</button>
                    <a class="button ghost" href="{{ route('catalog.index') }}">Limpiar</a>
                </div>
            </form>
        </aside>

        <div class="catalog-results">
            <div class="results-summary">
                <p>{{ $products->total() }} productos encontrados</p>
            </div>

            <div class="product-grid">
                @forelse ($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <p class="empty-state">No hay productos que coincidan con la busqueda.</p>
                @endforelse
            </div>

            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection
