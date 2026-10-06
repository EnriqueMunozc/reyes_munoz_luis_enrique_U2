@extends('layouts.app')

@section('title', 'Categorias | Administracion')

@section('content')
    <section class="page-hero compact">
        <p class="eyebrow">Administracion</p>
        <h1>Categorias</h1>
        <p>Organiza las rutas del catalogo y consulta sus productos asociados.</p>
    </section>

    <section class="section-wrap">
        <div class="admin-toolbar">
            <a class="button primary" href="{{ route('admin.categories.create') }}">Nueva categoria</a>
            <a class="text-link" href="{{ route('admin.dashboard') }}">Volver al panel</a>
        </div>

        @error('category')
            <p class="form-alert" role="alert">{{ $message }}</p>
        @enderror

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Slug</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>{{ $category->products_count }}</td>
                            <td><span @class(['status-badge', 'inactive' => ! $category->is_active])>{{ $category->is_active ? 'Activa' : 'Inactiva' }}</span></td>
                            <td class="table-actions">
                                <a href="{{ route('admin.categories.show', $category) }}">Ver</a>
                                <a href="{{ route('admin.categories.edit', $category) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Eliminar esta categoria? Esta accion no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No hay categorias registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $categories->links() }}</div>
    </section>
@endsection
