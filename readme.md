# Grand Line Store

Grand Line Store es una tienda web ficticia de articulos de anime inspirados en One Piece. El proyecto se desarrollara como actividad escolar y usara el control de versiones como parte central de la evidencia.

## Objetivo

Desarrollar una aplicacion web monolitica con Laravel, Blade, HTML, CSS, JavaScript y Microsoft SQL Server. La aplicacion debera permitir consultar productos, administrar catalogos, registrar usuarios, manejar carrito y generar pedidos ficticios sin pagos reales.

## Alcance Funcional Previsto

- Inicio con presentacion de la tienda y productos destacados.
- Catalogo con imagenes, descripcion, precios en MXN, busqueda y filtros por categoria.
- Registro, inicio y cierre de sesion.
- Roles de cliente y administrador.
- CRUD de productos y categorias para el administrador.
- Carrito con cantidades y totales.
- Pedidos ficticios, sin pasarela de pago ni cobros reales.
- Historial de pedidos del cliente.
- Diseno adaptable a computadora y celular.
- Datos de ejemplo de figuras, ropa, mangas y accesorios de One Piece.

## Tecnologias Acordadas

- Metodologia agil: Kanban.
- Columnas Kanban: Pendiente, En desarrollo, En revision y Terminado.
- Lenguaje principal: PHP.
- Framework: Laravel, en una version estable y mantenida compatible con PHP 8.3.
- Interfaz: Blade, HTML, CSS y JavaScript.
- Arquitectura: monolitica.
- Patron: Modelo, Vista y Controlador.
- Base de datos: Microsoft SQL Server.
- Administracion de base de datos: SQL Server Management Studio.
- Versionamiento: Git y GitHub.
- Flujo de trabajo: GitHub Flow.
- Desarrollo inicial: laptop Windows.

## Estado Actual

Fase 3 en desarrollo sobre la rama `feature/autenticacion-usuarios`, con SQL Server en la instancia local predeterminada y la base exclusiva `redline`.

Implementado en esta fase:

- Base Laravel estable compatible con PHP 8.3.
- Configuracion preparada para SQL Server mediante variables de entorno.
- Modelos `Category` y `Product` con relacion de categoria a productos.
- Migraciones para categorias y productos.
- Seeders repetibles con categorias y 12 articulos ficticios de One Piece.
- Inicio con productos destacados.
- Catalogo con busqueda, filtro por categoria y paginacion.
- Detalle de producto.
- Layout Blade compartido con navegacion y pie de pagina.
- Recurso local provisional para imagenes de productos.
- Registro de clientes, inicio y cierre de sesion con sesiones de Laravel.
- Roles `cliente` y `administrador`, con autorizacion del lado del servidor para el area administrativa.
- Comando interactivo para crear administradores.

No implementado todavia:

- CRUD administrativo.
- Carrito.
- Pedidos ficticios.
- Publicacion en cloud host.

## Entorno Verificado

Verificado localmente el 2026-10-06:

| Herramienta | Estado |
| --- | --- |
| PHP | 8.3.33 NTS x64 en `C:\php\php.exe` |
| Composer | 2.8.8 |
| Node.js | v22.15.0 |
| npm | 10.9.2 |
| Git | 2.49.0.windows.1 |
| Laravel | 13.10.1 con `laravel/framework` 13.34.0 |
| SQL Server PHP drivers | `sqlsrv` y `pdo_sqlsrv` cargan en `php -m` |
| ODBC | `ODBC Driver 17 for SQL Server` y `ODBC Driver 18 for SQL Server` instalados |
| SQL Server | Instancia predeterminada `MSSQLSERVER` en `localhost`, autenticacion integrada de Windows y base `redline` ONLINE |

Se creo una copia de seguridad de `C:\php\php.ini` antes de habilitar los drivers:

```text
C:\php\php.ini.bak-grand-line-store-20261001
```

## Ejecucion Local

La configuracion comprobada usa la instancia local predeterminada de SQL Server y la base exclusiva `redline`. No uses otra base ni incluyas `.env` en Git.

1. Instalar dependencias PHP:

   ```bash
   composer install
   ```

2. Instalar y compilar recursos:

   ```bash
   npm install
   npm run build
   ```

3. Crear `.env` desde el ejemplo y generar una clave de aplicacion si aun no existe:

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. Configurar SQL Server con autenticacion integrada de Windows en `.env`:

   ```env
   DB_CONNECTION=sqlsrv
   DB_HOST=localhost
   DB_PORT=
   DB_DATABASE=redline
   DB_USERNAME=
   DB_PASSWORD=
   DB_ENCRYPT=yes
   DB_TRUST_SERVER_CERTIFICATE=true
   ```

   Deja `DB_USERNAME`, `DB_PASSWORD` y `DB_PORT` vacios para que el conector use autenticacion integrada y resuelva la instancia local. `DB_TRUST_SERVER_CERTIFICATE=true` se limita al entorno de desarrollo local.

5. Confirmar la conexion activa antes de cambiar el esquema:

   ```bash
   php artisan tinker --execute="dump(DB::selectOne('SELECT DB_NAME() AS database_name')->database_name);"
   ```

   Debe mostrar `redline`.

6. En una base exclusiva nueva, aplicar las migraciones pendientes de forma dirigida:

   ```bash
   php artisan migrate --path=database/migrations/0001_01_01_000000_create_users_table.php
   php artisan migrate --path=database/migrations/0001_01_01_000001_create_cache_table.php
   php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php
   php artisan migrate --path=database/migrations/2026_10_01_000100_create_categories_table.php
   php artisan migrate --path=database/migrations/2026_10_01_000200_create_products_table.php
   php artisan migrate --path=database/migrations/2026_10_06_000300_add_role_to_users_table.php
   ```

   Comprueba el resultado sin modificar datos con:

   ```bash
   php artisan migrate:status
   ```

7. Cargar los datos de ejemplo repetibles:

   ```bash
   php artisan db:seed --class=Database\Seeders\StoreCatalogSeeder
   ```

8. Levantar servidor local:

   ```bash
   php artisan serve
   ```

   Abre `http://127.0.0.1:8000`.

## Cuentas de Fase 3

Una vez aplicada la migracion de roles, visitantes pueden registrarse como clientes o iniciar sesion. Las cuentas cliente acceden al catalogo y las cuentas administradoras al area protegida. Para crear una cuenta administradora, ejecuta localmente:

```bash
php artisan store:create-administrator
```

El comando solicita nombre, correo y contrasena de forma interactiva; no incluye ni muestra contrasenas en el proyecto.

Rutas disponibles en fase 2:

- `/` inicio con productos destacados.
- `/catalogo` catalogo con busqueda, filtro y paginacion.
- `/catalogo/{slug}` detalle de producto.
