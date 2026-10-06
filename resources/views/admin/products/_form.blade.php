@php($editing = isset($product))

<form class="admin-form" method="POST" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <label for="name">Nombre</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" maxlength="160" required autofocus>
    @error('name')<p class="field-error">{{ $message }}</p>@enderror

    <label for="category_id">Categoria</label>
    <select id="category_id" name="category_id" required>
        <option value="">Selecciona una categoria</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                {{ $category->name }}{{ $category->is_active ? '' : ' (inactiva)' }}
            </option>
        @endforeach
    </select>
    @error('category_id')<p class="field-error">{{ $message }}</p>@enderror

    <label for="description">Descripcion</label>
    <textarea id="description" name="description" rows="6" required>{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')<p class="field-error">{{ $message }}</p>@enderror

    <div class="form-grid-two">
        <div>
            <label for="price">Precio (MXN)</label>
            <input id="price" name="price" type="number" value="{{ old('price', $product->price ?? '') }}" min="0" max="99999999.99" step="0.01" required>
            @error('price')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="stock">Existencias</label>
            <input id="stock" name="stock" type="number" value="{{ old('stock', $product->stock ?? '') }}" min="0" max="2147483647" step="1" required>
            @error('stock')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <label for="image">Imagen (JPEG, PNG o WebP; maximo 2 MB)</label>
    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
    @error('image')<p class="field-error">{{ $message }}</p>@enderror

    @if ($editing)
        <div class="form-image-preview"><img src="{{ $product->imageUrl() }}" alt="Imagen actual de {{ $product->name }}"><span>Slug: <code>{{ $product->slug }}</code>. Se conserva al cambiar el nombre.</span></div>
    @endif

    <div class="form-grid-two checkbox-row">
        <input name="is_active" type="hidden" value="0">
        <label class="checkbox-field" for="is_active"><input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $product->is_active ?? true))> Producto activo en el catalogo</label>
        <input name="is_featured" type="hidden" value="0">
        <label class="checkbox-field" for="is_featured"><input id="is_featured" name="is_featured" type="checkbox" value="1" @checked(old('is_featured', $product->is_featured ?? false))> Destacar en inicio</label>
    </div>

    <div class="form-actions">
        <button class="button primary" type="submit">{{ $editing ? 'Guardar cambios' : 'Crear producto' }}</button>
        <a class="button ghost" href="{{ $editing ? route('admin.products.show', $product) : route('admin.products.index') }}">Cancelar</a>
    </div>
</form>
