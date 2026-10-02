# Caso Practico: Grand Line Store

Este documento es un borrador para la entrega final. Solo debe marcarse como completado lo que tenga evidencia real.

## 1. Justificacion de las plataformas y herramientas de versionamiento

Herramientas seleccionadas:

- Git como sistema de control de versiones.
- GitHub como plataforma remota para alojar el repositorio, compartir el enlace y revisar cambios mediante pull requests.

Justificacion:

Git permite registrar el historial del proyecto mediante commits, crear ramas por fase y revisar diferencias antes de integrar cambios. GitHub complementa ese flujo porque permite publicar el repositorio, abrir pull requests, compartir el enlace con el docente y mostrar evidencia del proceso.

Estado de evidencia:

- Repositorio remoto confirmado: `https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2.git`.
- Pendiente: capturas o enlaces de pull requests cuando se suban las ramas.

## 2. Flujo de trabajo del control de versiones

Flujo acordado: GitHub Flow.

Estructura:

1. `main` se mantiene como rama principal estable.
2. Cada fase se trabaja en una rama independiente.
3. Los cambios se guardan con commits descriptivos.
4. La rama se sube a GitHub.
5. Se abre un pull request hacia `main`.
6. Se revisa el diff y la evidencia.
7. Se integra a `main` cuando la fase este terminada.

Rama de fase 1:

```text
chore/preparacion-proyecto
```

Estado de evidencia:

- Pendiente: subir la rama y abrir pull request.
- Pendiente: captura del pull request y del historial de commits.

## 3. Parametros de configuracion de las plataformas y herramientas de versionamiento

Parametros locales verificados el 2026-10-01:

| Elemento | Valor |
| --- | --- |
| Git | `git version 2.49.0.windows.1` |
| Rama base | `main` |
| Remoto | `origin` |
| URL remota | `https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2.git` |
| Usuario Git | `Luis Enrique Reyes Muñoz` |
| Correo Git | `23040114@alumno.utc.edu.mx` |
| Rama predeterminada para nuevos repositorios | `master` |

Parametros recomendados para el proyecto:

- Proteger o tratar `main` como rama estable.
- Usar ramas por fase o funcionalidad.
- No subir `.env`, contrasenas, dependencias instaladas ni archivos de base de datos.
- Usar pull requests para revisar cambios antes de integrarlos.

Estado de evidencia:

- Confirmado localmente.
- Pendiente: capturas de configuracion en GitHub si el docente las solicita.

## 4. Enlace del repositorio en funcionamiento con la estructura del flujo de trabajo; compartir el repositorio de GitHub y adjuntar captura de pantalla con el sitio funcionando en un cloud host

Repositorio GitHub:

```text
https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2
```

Estructura del flujo de trabajo:

- Rama principal: `main`.
- Rama de preparacion: `chore/preparacion-proyecto`.
- Ramas futuras: `feature/base-laravel-sqlserver`, `feature/autenticacion-roles`, `feature/catalogo-crud`, `feature/carrito-pedidos`, `docs/evidencias-finales`.

Cloud host:

- Pendiente de seleccion.
- Pendiente de despliegue real.
- Pendiente de URL publica o evidencia de acceso.
- Pendiente de captura de pantalla con el sitio funcionando en cloud host.

Nota importante: la ejecucion local no sustituye la evidencia del cloud host. No se debe inventar captura, URL publicada ni resultado de despliegue.

## Investigacion Inicial De Cloud Host

Consulta realizada el 2026-10-01. La decision final queda pendiente para la fase 6.

| Opcion | Compatibilidad observada | Requisitos probables | Costos y limites revisados | Estado para el proyecto |
| --- | --- | --- | --- | --- |
| Azure App Service | Soporta aplicaciones PHP y permite configurar versiones de PHP. Azure documenta App Service para PHP y variables de configuracion. Es una opcion natural si se usa SQL Server o Azure SQL. | Configurar PHP, Composer, variables de entorno, directorio `public`, extensiones/controladores para SQL Server o contenedor personalizado si el runtime no trae lo necesario. | Costos dependen del plan de App Service y del servicio de base de datos. Requiere revisar credito disponible o plan educativo. | Candidato fuerte por relacion con SQL Server. Pendiente validar costo y soporte de `sqlsrv` en el plan elegido. |
| Laravel Cloud | Plataforma administrada para Laravel. Documenta soporte para apps Laravel y PHP recientes. Permite traer una base de datos existente con conexion publica. | Cuenta de Laravel Cloud, repositorio Git, variables de entorno, base SQL Server accesible publicamente si se conserva este motor. | Pricing por uso; la pagina de precios muestra computo desde planes pequenos y credito inicial segun disponibilidad vigente. | Candidato comodo para Laravel, pero se debe validar conexion real a SQL Server y costo. |
| VPS con Laravel Forge | Forge administra servidores para Laravel y despliegues desde Git. Permite mayor control para instalar ODBC y extensiones SQL Server en el servidor. | Suscripcion Forge mas costo del proveedor VPS. Configurar servidor, PHP, Nginx, Composer, Node, extensiones `sqlsrv`, ODBC y variables. | Forge tiene plan mensual y el proveedor VPS se cobra aparte. | Candidato tecnico flexible. Puede ser mas costoso o complejo para una entrega escolar. |
| Render con Docker | Render permite desplegar apps en lenguajes no nativos mediante Docker. PHP/Laravel puede ejecutarse en contenedor. | Crear Dockerfile con PHP, Composer, Node, extensiones `sqlsrv`, ODBC Driver 18 y configuracion de Laravel. SQL Server deberia estar en servicio externo. | Render documenta servicios web, builds, variables y planes con uso gratuito/compute segun disponibilidad. No ofrece SQL Server administrado como datastore principal. | Posible, pero requiere Docker y base SQL Server externa. Mas complejo para esta actividad. |

Fuentes base:

- https://laravel.com/docs
- https://laravel.com/cloud
- https://laravel.com/forge
- https://learn.microsoft.com/en-us/azure/app-service/configure-language-php
- https://learn.microsoft.com/en-us/sql/connect/php/
- https://learn.microsoft.com/en-us/sql/connect/odbc/
- https://render.com/docs

## Datos Que Faltan Para Continuar Con SQL Server

No se deben escribir contrasenas en este documento ni en el chat.

Para iniciar la fase 2 faltan:

- nombre del servidor o instancia de SQL Server;
- puerto, si se usa uno distinto al predeterminado;
- nombre de la base de datos;
- tipo de autenticacion: Windows o SQL Server;
- usuario, solo si se usara autenticacion SQL Server;
- decision sobre cifrado local: `encrypt`, `trust_server_certificate` u opcion equivalente.
