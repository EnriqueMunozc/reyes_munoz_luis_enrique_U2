# Caso Practico: Grand Line Store

Este documento registra la evidencia para la entrega escolar. Solo se marca como completado lo que tenga evidencia real.

## 1. Justificacion De Las Plataformas Y Herramientas De Versionamiento

Herramientas seleccionadas:

- Git como sistema de control de versiones.
- GitHub como plataforma remota para alojar el repositorio, compartir el enlace y revisar cambios mediante pull requests.

Git registra el historial del proyecto mediante commits, permite trabajar por ramas y revisar diferencias antes de integrar cambios. GitHub complementa ese flujo al publicar el repositorio, abrir pull requests y compartir evidencia con el docente.

Estado de evidencia:

- Repositorio remoto: `https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2.git`.
- Historial de fases integrado mediante ramas y pull requests.
- Pendiente: adjuntar capturas o enlaces de los pull requests que solicite el docente.

## 2. Flujo De Trabajo Del Control De Versiones

Flujo acordado: GitHub Flow.

1. `main` se mantiene como rama principal estable.
2. Cada fase se trabaja en una rama independiente.
3. Los cambios se guardan con commits descriptivos.
4. La rama se sube a GitHub y se abre un pull request hacia `main`.
5. Se revisa el diff y la evidencia antes de integrar.

Ramas de evidencia utilizadas:

- `chore/preparacion-proyecto`
- `feature/carrito-pedidos`
- `feature/imagenes-catalogo`
- `feature/docker-entrega`

## 3. Parametros De Configuracion De Las Herramientas De Versionamiento

Parametros locales verificados el 2026-10-01:

| Elemento | Valor |
| --- | --- |
| Git | `git version 2.49.0.windows.1` |
| Rama base | `main` |
| Remoto | `origin` |
| URL remota | `https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2.git` |
| Usuario Git | `Luis Enrique Reyes Muñoz` |
| Correo Git | `23040114@alumno.utc.edu.mx` |

Reglas del repositorio:

- No subir `.env`, contrasenas, dependencias instaladas, volumenes ni archivos de bases de datos.
- Usar ramas por fase, commits descriptivos y pull requests hacia `main`.
- Conservar secretos de Docker exclusivamente en `.env.docker`, ignorado por Git.

## 4. Enlace Del Repositorio Y Ejecucion Local Con Docker

Repositorio GitHub:

```text
https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2
```

Autorizacion del profesor:

- Docker local sustituye el requisito de cloud host para esta entrega escolar.
- No se afirma que la aplicacion este publicada en Internet ni se incluye una URL publica.
- La tienda usa contenedores Linux para Laravel/PHP-FPM, Nginx y SQL Server Developer de demostracion.
- Los secretos se generan localmente en `.env.docker` y no se incluyen en Git.

Evidencias de entrega:

- Enlace del repositorio y su historial de commits y pull requests.
- Capturas reales de Docker Desktop o de `docker compose ps` con los contenedores activos.
- Capturas reales de `http://localhost:8080` mostrando catalogo, autenticacion, CRUD, carrito y pedido ficticio.

Estado de evidencia:

- Pendiente: iniciar el motor Docker, ejecutar los contenedores y capturar esas evidencias.
- No se deben inventar capturas, una URL cloud ni resultados de despliegue no ejecutados.
