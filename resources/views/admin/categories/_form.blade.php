@php($editing = isset($category))

<form class="admin-form" method="POST" action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <label for="name">Nombre</label>
    <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}" maxlength="120" required autofocus>
    @error('name')<p class="field-error">{{ $message }}</p>@enderror

    <label for="description">Descripcion</label>
    <textarea id="description" name="description" rows="5">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')<p class="field-error">{{ $message }}</p>@enderror

    @if ($editing)
        <p class="form-hint">Slug actual: <code>{{ $category->slug }}</code>. Se conserva al cambiar el nombre.</p>
    @endif

    <input name="is_active" type="hidden" value="0">
    <label class="checkbox-field" for="is_active">
        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $category->is_active ?? true))>
        Categoria activa en el catalogo
    </label>

    <div class="form-actions">
        <button class="button primary" type="submit">{{ $editing ? 'Guardar cambios' : 'Crear categoria' }}</button>
        <a class="button ghost" href="{{ $editing ? route('admin.categories.show', $category) : route('admin.categories.index') }}">Cancelar</a>
    </div>
</form>
