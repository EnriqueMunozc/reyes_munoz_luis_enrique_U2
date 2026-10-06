@extends('layouts.app')

@section('title', 'Productos | Administracion')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Administracion</p>
        <h1>Productos</h1>
        <p>Consulta, filtra y administra el inventario de la tienda.</p>
    </section>

    <section class="section-wrap">
        <div class="admin-toolbar">
            <a class="button primary" href="{{ route('admin.products.create') }}">Nuevo producto</a>
            <a class="text-link" href="{{ route('admin.dashboard') }}">Volver al panel</a>
        </div>

        <form class="admin-filters" method="GET" action="{{ route('admin.products.index') }}">
            <label for="buscar">Buscar</label>
            <input id="buscar" name="buscar" type="search" value="{{ $search }}" placeholder="Nombre o descripcion">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria">
                <option value="">Todas las categorias</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $category->id === $categoryId)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button class="button primary" type="submit">Filtrar</button>
            <a class="button ghost" href="{{ route('admin.products.index') }}">Limpiar</a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table products-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoria</th>
                        <th>Precio</th>
                        <th>Existencias</th>
                        <th>Estado</th>
                        <th><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="product-cell"><img src="{{ $product->imageUrl() }}" alt=""><div><strong>{{ $product->name }}</strong><small><code>{{ $product->slug }}</code></small></div></td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format((float) $product->price, 2) }} MXN</td>
                            <td>{{ $product->stock }}</td>
                            <td><span @class(['status-badge', 'inactive' => ! $product->is_active])>{{ $product->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="table-actions">
                                <a href="{{ route('admin.products.show', $product) }}">Ver</a>
                                <a href="{{ route('admin.products.edit', $product) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Eliminar este producto? Esta accion no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No hay productos que coincidan con el filtro.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $products->links() }}</div>
    </section>
@endsection
