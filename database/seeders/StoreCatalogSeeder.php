<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StoreCatalogSeeder extends Seeder
{
    /**
     * Seed repeatable sample data for the school catalog.
     */
    public function run(): void
    {
        $categories = collect([
            [
                'name' => 'Figuras',
                'slug' => 'figuras',
                'description' => 'Figuras coleccionables inspiradas en tripulaciones, capitanes y momentos de aventura.',
            ],
            [
                'name' => 'Ropa',
                'slug' => 'ropa',
                'description' => 'Prendas casuales con estilo nautico para fans de la Grand Line.',
            ],
            [
                'name' => 'Mangas',
                'slug' => 'mangas',
                'description' => 'Tomos y ediciones ficticias para ampliar la coleccion.',
            ],
            [
                'name' => 'Accesorios',
                'slug' => 'accesorios',
                'description' => 'Articulos pequenos para acompanar cualquier travesia.',
            ],
        ])->mapWithKeys(function (array $category) {
            $model = Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true],
            );

            return [$category['slug'] => $model];
        });

        $products = [
            [
                'category' => 'figuras',
                'name' => 'Figura Capitan del Sombrero de Paja',
                'slug' => 'figura-capitan-sombrero-paja',
                'description' => 'Figura de coleccion con pose de aventura, base tipo cubierta y acabado mate.',
                'price' => 899.00,
                'stock' => 18,
                'is_featured' => true,
            ],
            [
                'category' => 'figuras',
                'name' => 'Figura Espadachin de Tres Katanas',
                'slug' => 'figura-espadachin-tres-katanas',
                'description' => 'Pieza decorativa con tres espadas, capa verde y base de puerto.',
                'price' => 949.00,
                'stock' => 12,
                'is_featured' => true,
            ],
            [
                'category' => 'figuras',
                'name' => 'Figura Navegante del Nuevo Mundo',
                'slug' => 'figura-navegante-nuevo-mundo',
                'description' => 'Figura colorida con baston climatico y detalles de mapa marino.',
                'price' => 829.00,
                'stock' => 15,
                'is_featured' => false,
            ],
            [
                'category' => 'ropa',
                'name' => 'Playera Bandera Pirata Roja',
                'slug' => 'playera-bandera-pirata-roja',
                'description' => 'Playera de algodon con estampado frontal inspirado en banderas de tripulacion.',
                'price' => 329.00,
                'stock' => 40,
                'is_featured' => true,
            ],
            [
                'category' => 'ropa',
                'name' => 'Sudadera Grand Line Azul Marino',
                'slug' => 'sudadera-grand-line-azul-marino',
                'description' => 'Sudadera con capucha, bolsillo frontal y bordado de brujula en manga.',
                'price' => 699.00,
                'stock' => 22,
                'is_featured' => true,
            ],
            [
                'category' => 'ropa',
                'name' => 'Gorra Puerto del Este',
                'slug' => 'gorra-puerto-este',
                'description' => 'Gorra ajustable con parche bordado y visera curva.',
                'price' => 249.00,
                'stock' => 35,
                'is_featured' => false,
            ],
            [
                'category' => 'mangas',
                'name' => 'Manga Saga del Mar Azul Volumen 1',
                'slug' => 'manga-saga-mar-azul-volumen-1',
                'description' => 'Tomo ficticio de coleccion con portada alternativa en tonos pergamino.',
                'price' => 159.00,
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'category' => 'mangas',
                'name' => 'Manga Ruta a la Isla Celeste',
                'slug' => 'manga-ruta-isla-celeste',
                'description' => 'Edicion ficticia con separador incluido y detalles metalizados.',
                'price' => 179.00,
                'stock' => 27,
                'is_featured' => true,
            ],
            [
                'category' => 'mangas',
                'name' => 'Manga Batalla en el Archipielago',
                'slug' => 'manga-batalla-archipielago',
                'description' => 'Volumen con portada de accion, ideal para coleccion escolar de muestra.',
                'price' => 169.00,
                'stock' => 24,
                'is_featured' => false,
            ],
            [
                'category' => 'accesorios',
                'name' => 'Llavero Brujula de Log Pose',
                'slug' => 'llavero-brujula-log-pose',
                'description' => 'Llavero metalico con acabado antiguo y aro reforzado.',
                'price' => 119.00,
                'stock' => 60,
                'is_featured' => true,
            ],
            [
                'category' => 'accesorios',
                'name' => 'Taza Mapa de la Grand Line',
                'slug' => 'taza-mapa-grand-line',
                'description' => 'Taza ceramica con mapa ilustrado y capacidad de 325 ml.',
                'price' => 189.00,
                'stock' => 44,
                'is_featured' => false,
            ],
            [
                'category' => 'accesorios',
                'name' => 'Poster Tripulacion al Atardecer',
                'slug' => 'poster-tripulacion-atardecer',
                'description' => 'Poster tamano mediano con acabado satinado y colores de aventura maritima.',
                'price' => 139.00,
                'stock' => 50,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $categories[$product['category']]->id,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'image_path' => 'images/products/placeholder-product.svg',
                    'is_active' => true,
                    'is_featured' => $product['is_featured'],
                ],
            );
        }
    }
}
