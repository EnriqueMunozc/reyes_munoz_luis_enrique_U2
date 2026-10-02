# Versionamiento

Grand Line Store usara Git y GitHub para mantener historial, revisar cambios y evidenciar el flujo de trabajo de la actividad.

## Justificacion de Herramientas

Git permite registrar cambios por commits, trabajar en ramas y volver a consultar el historial del proyecto. GitHub permite alojar el repositorio remoto, compartir el enlace, abrir pull requests y mostrar evidencia del flujo de colaboracion.

Para este proyecto escolar se usaran porque:

- facilitan explicar que cambio se hizo y por que;
- separan el trabajo por fases;
- permiten revisar diferencias antes de integrar cambios;
- generan evidencia visible para los criterios de evaluacion;
- son herramientas comunes en proyectos web reales.

## Flujo de Trabajo: GitHub Flow

El flujo acordado es GitHub Flow:

1. Mantener `main` como rama principal estable.
2. Crear una rama por fase o funcionalidad.
3. Hacer commits pequenos y descriptivos.
4. Subir la rama a GitHub.
5. Abrir un pull request hacia `main`.
6. Revisar cambios, pruebas y evidencia.
7. Integrar a `main` solo cuando la fase este terminada.

En esta fase no se hara push ni merge. La rama local de trabajo es:

```bash
chore/preparacion-proyecto
```

## Convenciones de Ramas

Usar nombres cortos en minusculas:

```text
chore/preparacion-proyecto
feature/base-laravel-sqlserver
feature/autenticacion-roles
feature/catalogo-crud
feature/carrito-pedidos
docs/evidencias-finales
fix/nombre-del-problema
```

Prefijos sugeridos:

- `chore/` para preparacion, configuracion o tareas de mantenimiento.
- `feature/` para funciones nuevas.
- `fix/` para correcciones.
- `docs/` para documentacion.

## Convenciones de Commits

Usar mensajes claros en espanol, con verbo en infinitivo o forma corta:

```text
docs: documentar preparacion inicial del proyecto
chore: configurar gitignore inicial de Laravel
feature: agregar catalogo de productos
fix: corregir validacion de precio
```

Recomendaciones:

- Un commit debe agrupar cambios relacionados.
- No incluir `.env`, contrasenas, archivos de base de datos ni dependencias instaladas.
- Revisar `git diff` antes de confirmar.
- Evitar commits con mensajes genericos como `cambios` o `final`.

## Parametros de Git Documentados

Verificado localmente el 2026-10-01:

| Parametro | Valor |
| --- | --- |
| `git --version` | `git version 2.49.0.windows.1` |
| Rama base revisada | `main` |
| Seguimiento remoto | `main...origin/main` |
| Remoto `origin` | `https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2.git` |
| `user.name` | `Luis Enrique Reyes Muñoz` |
| `user.email` | `23040114@alumno.utc.edu.mx` |
| `init.defaultBranch` | `master` |

Nota: aunque `init.defaultBranch` esta en `master`, este repositorio ya trabaja con `main`. No es necesario cambiarlo para esta fase, pero conviene documentarlo y, si se desea, ajustar nuevos repositorios con:

```bash
git config --global init.defaultBranch main
```

## Comandos De Trabajo Sugeridos

Revisar estado:

```bash
git status -sb
```

Revisar diferencias:

```bash
git diff
```

Agregar cambios de documentacion:

```bash
git add readme.md docs/PLAN.md docs/KANBAN.md docs/VERSIONAMIENTO.md docs/CASO_PRACTICO.md .gitignore
```

Crear commit:

```bash
git commit -m "docs: preparar plan inicial de Grand Line Store"
```

Subir rama:

```bash
git push -u origin chore/preparacion-proyecto
```

Abrir pull request:

```bash
gh pr create --base main --head chore/preparacion-proyecto --title "Preparar documentacion inicial del proyecto" --body "Documenta la fase 1 de Grand Line Store: plan, Kanban, versionamiento, caso practico y gitignore inicial."
```

Si no se usa GitHub CLI, abrir en el navegador:

```text
https://github.com/EnriqueMunozc/reyes_munoz_luis_enrique_U2/pulls
```
