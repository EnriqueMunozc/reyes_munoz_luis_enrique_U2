# Grand Line Store

Grand Line Store es una tienda web ficticia inspirada en One Piece. Permite explorar productos y realizar pedidos de demostracion sin pagos reales.

## Funciones

- Catalogo con productos destacados, busqueda, filtro por categoria y paginacion.
- Registro de clientes, inicio y cierre de sesion.
- Roles de cliente y administrador.
- Panel administrativo con CRUD de categorias y productos.
- Carga de imagenes para productos desde el panel administrativo.
- Carrito de compras con validacion de existencias.
- Pedidos ficticios e historial de pedidos para usuarios autenticados.

## Tecnologias

- PHP 8.3 y Laravel.
- Blade, HTML, CSS y JavaScript.
- Arquitectura monolitica con MVC.
- Microsoft SQL Server.
- Docker Compose, Nginx y PHP-FPM.

## Requisitos

- Docker Desktop con el motor Linux iniciado.
- PowerShell.
- Al menos 4 GB de memoria disponibles para Docker.

## Primera Instalacion

Desde la raiz del proyecto, ejecuta:

```powershell
.\scripts\prepare-docker.ps1
docker compose --env-file .env.docker build
docker compose --env-file .env.docker up -d sqlserver
docker compose --env-file .env.docker --profile init build init
docker compose --env-file .env.docker --profile init run --rm init
docker compose --env-file .env.docker up -d app web
docker compose --env-file .env.docker ps
```

El script genera `.env.docker` con la configuracion local necesaria. Este archivo contiene secretos y no debe subirse a Git.

## Inicios Posteriores

Para iniciar la tienda despues de la primera instalacion:

```powershell
docker compose --env-file .env.docker up -d
docker compose --env-file .env.docker ps
```

## Direccion Local

Abre la tienda en [http://localhost:8080](http://localhost:8080).

## Detener La Tienda

Para detener los contenedores sin eliminar los datos:

```powershell
docker compose --env-file .env.docker stop
```

## Crear Un Administrador

Con los contenedores iniciados, ejecuta:

```powershell
docker compose --env-file .env.docker exec app php artisan store:create-administrator
```

El comando solicita nombre, correo y contrasena de forma interactiva.
