@extends('layouts.app')

@section('title', 'Grand Line Store | Inicio')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <p class="eyebrow">Catalogo escolar conectado a SQL Server</p>
            <h1>Articulos de anime para una tripulacion lista para zarpar</h1>
            <p>
                Grand Line Store reune figuras, ropa, mangas y accesorios ficticios inspirados en aventuras maritimas.
                Esta fase muestra productos destacados desde la base de datos.
            </p>
            <div class="hero-actions">
                <a class="button primary" href="{{ route('catalog.index') }}">Ver catalogo</a>
            </div>
        </div>

        <div class="hero-panel" aria-label="Resumen de catalogo">
            <span>Fase 2</span>
            <strong>{{ $featuredProducts->count() }}</strong>
            <p>productos destacados cargados desde SQL Server cuando se ejecuten migraciones y seeders.</p>
        </div>
    </section>

    <section class="section-wrap">
        <div class="section-heading">
            <p class="eyebrow">Categorias</p>
            <h2>Rutas del catalogo</h2>
        </div>

        <div class="category-grid">
            @forelse ($categories as $category)
                <a class="category-tile" href="{{ route('catalog.index', ['categoria' => $category->slug]) }}">
                    <strong>{{ $category->name }}</strong>
                    <span>{{ $category->products_count }} productos</span>
                    <p>{{ $category->description }}</p>
                </a>
            @empty
                <p class="empty-state">Aun no hay categorias cargadas. Ejecuta las migraciones y seeders sobre la base SQL Server del proyecto.</p>
            @endforelse
        </div>
    </section>

    <section class="section-wrap">
        <div class="section-heading row-heading">
            <div>
                <p class="eyebrow">Destacados</p>
                <h2>Mercancia para iniciar la aventura</h2>
            </div>
            <a class="text-link" href="{{ route('catalog.index') }}">Explorar todo</a>
        </div>

        <div class="product-grid">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="empty-state">No hay productos destacados todavia. Los datos apareceran al ejecutar `php artisan db:seed`.</p>
            @endforelse
        </div>
    </section>
@endsection
