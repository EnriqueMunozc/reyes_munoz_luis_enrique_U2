<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ProductImageManager
{
    public const PLACEHOLDER_PATH = 'images/products/placeholder-product.svg';

    public function store(UploadedFile $image): string
    {
        $path = Storage::disk('public')->putFile('products', $image);

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('No se pudo guardar la imagen del producto.');
        }

        return $path;
    }

    public function url(?string $path): string
    {
        if ($this->isManagedPath($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset(self::PLACEHOLDER_PATH);
    }

    public function deleteIfUnused(?string $path): void
    {
        if (! $this->isManagedPath($path) || Product::query()->where('image_path', $path)->exists()) {
            return;
        }

        try {
            if (! Storage::disk('public')->delete($path)) {
                Log::warning('No se pudo eliminar una imagen administrada sin referencias.', ['path' => $path]);
            }
        } catch (Throwable $exception) {
            Log::warning('Error al eliminar una imagen administrada sin referencias.', [
                'path' => $path,
                'exception' => $exception,
            ]);
        }
    }

    public function isManagedPath(?string $path): bool
    {
        return is_string($path)
            && str_starts_with($path, 'products/')
            && ! str_contains($path, '..')
            && ! str_contains($path, '\\');
    }
}
