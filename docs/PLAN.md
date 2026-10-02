# Plan Por Fases

Este plan organiza Grand Line Store por fases pequenas para que el avance sea facil de revisar, defender y versionar. La metodologia acordada es Kanban y el flujo de Git sera GitHub Flow.

## Fase 1. Preparacion y Planificacion

Objetivo: dejar documentado el proyecto, el alcance, el flujo de trabajo y los requisitos iniciales.

Criterios de terminacion:

- README actualizado con descripcion, alcance, tecnologias y estado real.
- `docs/PLAN.md` creado.
- `docs/KANBAN.md` creado con columnas Pendiente, En desarrollo, En revision y Terminado.
- `docs/VERSIONAMIENTO.md` creado con GitHub Flow, ramas, commits y parametros de Git.
- `docs/CASO_PRACTICO.md` creado con los cuatro apartados exactos de evaluacion.
- `.gitignore` inicial preparado para Laravel.
- Entorno local revisado sin instalar Laravel.
- Requisitos faltantes de SQL Server documentados.
- Opciones de cloud host investigadas y marcadas como pendientes de decision.

Estado: listo para revision.

## Fase 2. Base de Laravel, SQL Server, Migraciones, Seeders y Diseno General

Objetivo: crear la aplicacion Laravel base y conectarla a SQL Server.

Criterios de terminacion:

- Proyecto Laravel instalado con version estable y mantenida compatible con PHP 8.3.
- Conexion a SQL Server configurada mediante `.env`, sin subir secretos.
- Migraciones iniciales para usuarios, roles, categorias, productos, carrito y pedidos.
- Seeders con datos de ejemplo de figuras, ropa, mangas y accesorios.
- Layout Blade base con navegacion y estilo inicial adaptable.
- Pagina de inicio funcional con datos persistidos.
- Instrucciones de instalacion local actualizadas.

Estado: pendiente.

## Fase 3. Usuarios, Autenticacion y Permisos

Objetivo: implementar registro, inicio de sesion, cierre de sesion y roles.

Criterios de terminacion:

- Registro e inicio de sesion funcionando con validacion del servidor.
- Contrasenas guardadas con hash.
- Proteccion CSRF activa en formularios.
- Roles de cliente y administrador.
- Middleware o politicas para restringir funciones de administrador.
- Pruebas manuales documentadas.

Estado: pendiente.

## Fase 4. Catalogo y CRUD de Productos y Categorias

Objetivo: permitir consulta publica del catalogo y administracion por usuarios autorizados.

Criterios de terminacion:

- Catalogo con busqueda y filtros por categoria.
- Productos con nombre, descripcion, precio MXN, categoria, imagen provisional o final y estado.
- CRUD de productos para administrador.
- CRUD de categorias para administrador.
- Validacion de formularios y mensajes de error.
- Vistas responsivas para computadora y celular.

Estado: pendiente.

## Fase 5. Carrito y Pedidos Ficticios

Objetivo: implementar compra simulada sin cobros reales.

Criterios de terminacion:

- Carrito por usuario con cantidades editables.
- Calculo de subtotales y total.
- Confirmacion de pedido ficticio.
- Historial de pedidos del cliente.
- Sin pasarela de pago ni datos bancarios.
- Persistencia en SQL Server.

Estado: pendiente.

## Fase 6. Pruebas, Instalacion Reproducible y Publicacion en Cloud Host

Objetivo: preparar evidencia real del sistema funcionando.

Criterios de terminacion:

- Instrucciones reproducibles para instalar dependencias, configurar `.env`, migrar y sembrar datos.
- Pruebas manuales de flujos principales.
- Cloud host elegido y configurado.
- Sitio publicado en cloud host.
- Conexion a SQL Server documentada segun el entorno elegido.
- Captura del sitio funcionando en cloud host.
- Enlace publico o evidencia de acceso registrada.

Estado: pendiente.

## Fase 7. Documento Final y Evidencias

Objetivo: cerrar la entrega con explicacion y pruebas de cumplimiento.

Criterios de terminacion:

- Caso practico final actualizado.
- Justificacion de herramientas de versionamiento.
- Flujo de trabajo explicado con evidencia de ramas, commits y pull requests.
- Parametros de configuracion documentados.
- Enlace del repositorio y captura del sitio en cloud host adjuntos.
- Diferencia clara entre funciones terminadas y pendientes.

Estado: pendiente.
