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

Fase 2 en desarrollo sobre la rama `feature/base-aplicacion`, creada desde `chore/preparacion-proyecto` porque los cambios de fase 1 aun no estaban confirmados ni integrados.

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

No implementado todavia:

- Registro, inicio y cierre de sesion.
- Roles de cliente y administrador.
- CRUD administrativo.
- Carrito.
- Pedidos ficticios.
- Publicacion en cloud host.

## Entorno Verificado

Verificado localmente el 2026-10-01:

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

Se creo una copia de seguridad de `C:\php\php.ini` antes de habilitar los drivers:

```text
C:\php\php.ini.bak-grand-line-store-20261001
```

## Ejecucion Local Prevista

Antes de ejecutar migraciones confirma que la base `grand_line_store` o el nombre elegido sea exclusiva para este proyecto escolar.

1. Instalar dependencias PHP:

   ```bash
   composer install
   ```

2. Instalar y compilar recursos:

   ```bash
   npm install
   npm run build
   ```

3. Crear `.env` desde el ejemplo y completar valores reales sin subirlo a Git:

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. Configurar SQL Server en `.env`:

   ```env
   DB_CONNECTION=sqlsrv
   DB_HOST=TU_SERVIDOR_O_INSTANCIA
   DB_PORT=1433
   DB_DATABASE=grand_line_store
   DB_USERNAME=
   DB_PASSWORD=
   DB_ENCRYPT=yes
   DB_TRUST_SERVER_CERTIFICATE=false
   ```

5. Ejecutar migraciones y seeders solo contra la base exclusiva del proyecto:

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. Levantar servidor local:

   ```bash
   php artisan serve
   ```

Rutas disponibles en fase 2:

- `/` inicio con productos destacados.
- `/catalogo` catalogo con busqueda, filtro y paginacion.
- `/catalogo/{slug}` detalle de producto.
