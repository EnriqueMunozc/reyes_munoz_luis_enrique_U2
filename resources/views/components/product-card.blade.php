<article class="product-card">
    <a class="product-media" href="{{ route('catalog.show', $product) }}" aria-label="Ver {{ $product->name }}">
        <img src="{{ $product->imageUrl() }}" alt="Imagen de {{ $product->name }}">
    </a>

    <div class="product-body">
        <p class="product-category">{{ $product->category->name }}</p>
        <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
        <p>{{ \Illuminate\Support\Str::limit($product->description, 105) }}</p>
    </div>

    <div class="product-meta">
        <strong>${{ number_format((float) $product->price, 2) }} MXN</strong>
        <span>{{ $product->stock }} disponibles</span>
    </div>
</article>
