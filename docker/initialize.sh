#!/bin/sh
set -eu

php docker/create-database.php
php artisan migrate --force

catalog_counts=$(php docker/catalog-status.php)

case "$catalog_counts" in
    0:0)
        php artisan db:seed --class=Database\\Seeders\\StoreCatalogSeeder --force
        ;;
    4:12)
        echo "Catalogo de demostracion existente; no se ejecuta el seeder."
        ;;
    *)
        echo "El catalogo Docker esta parcialmente inicializado; no se ejecuta el seeder para no sobrescribir datos." >&2
        exit 1
        ;;
esac

if [ ! -e public/storage ]; then
    php artisan storage:link
fi

echo "Inicializacion Docker completada."
