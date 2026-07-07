# AGENTS.md — MigrarSP

## Overview
Legacy PHP web app (no framework, no Composer) for MySQL migration utilities: compare stored procedures, extract referenced tables, generate SQL migration scripts, and compare/copy table schemas.

## Commands
```bash
docker-compose up          # dev: http://localhost:8080 (volume-mounted, hot-reload)
docker build -t migrarsp . # production build
```

No build step, no tests, no linter, no CI.

## Stack
- **Language:** PHP 8.1, plain procedural (no classes, no autoloading)
- **DB driver:** `mysqli` extension only (not PDO)
- **Server:** Apache 2 with `mod_rewrite`, configured via `Dockerfile`
- **Source:** all 18 PHP files in `src/` (flat structure)

## Architecture
All pages `include('header.php')` / `include('footer.php')` for shared HTML chrome. No router — each `.php` file is its own entrypoint accessed directly via URL.

| File | Role |
|---|---|
| `index.php` | Homepage / nav hub |
| `index_compsp.php` | Form: compare stored procedures between two servers |
| `comparasp.php` | Handler: lists procedures present on one server but missing on the other |
| `compsp.php` | Handler: side-by-side diff of a procedure's body (uses system `diff`) |
| `index_extrae.php` | Form: extract table names referenced by stored procedures |
| `extraetablas.php` | Handler: parses SP source for table names, compares against destination |
| `index_migrasp.php` | Form: generate SQL migration scripts from SP list |
| `listar.php` | Handler: outputs downloadable `DROP + CREATE PROCEDURE` scripts |
| `copiartabla.php` | Outputs `CREATE TABLE` + `INSERT` scripts (includes data for tables < 5000 rows) |
| `comptabla.php` | Compares table columns between servers, generates ALTER TABLE SQL |
| `config_servers.php` | Server list management page |
| `edit_server.php` | Add/edit server configuration form |
| `save_server.php` | Saves/deletes server config to XML |
| `servidores.php` | Loads `servidores.xml`, provides `Conectar_Con($serverObj)` to open mysqli connections |
| `basecomp.php` | Shared helpers: `eliminar_comentarios()`, `extraer_palabra_despues()`, `comparador()` |
| `header.php` / `footer.php` | Shared HTML shell with nav bar |
| `style.css` | All CSS (dark theme, Inter/Outfit fonts) |
| `servidores.xml` | **Server config data file** — contains DB host/user/pass/db for each configured server |

## Key conventions & gotchas

### XML-based config
`servidores.php` loads `servidores.xml` **relative to CWD** (`simplexml_load_file('servidores.xml')`). All pages assume they execute with `src/` as the working directory. The XML schema for each server:
```xml
<servidor>
    <nombre>Display Name</nombre>
    <host>hostname</host>
    <usuario>user</usuario>
    <clave>password</clave>
    <puerto>3306</puerto>
    <base>database_name</base>
</servidor>
```

### `compsp.php` requires `diff`
This page writes procedure bodies to temp files in `/tmp/` and shells out to `diff`. It also calls `highlight_string()` and `highlight_file()` (PHP built-ins). The Docker base image (`php:8.1-apache`) includes `diff` by default.

### Error handling
- `mysqli_report(MYSQLI_REPORT_OFF)` suppresses mysqli errors/warnings at the driver level.
- `@session_start()` suppresses session warnings.
- `or die(...)` is used for connection failures.
- There is no exception handling or logging.

### Security note
`servidores.xml` contains **plaintext database passwords** for 14 MySQL servers. Never commit real credentials. Consider `.gitignore` for this file or replacing values with placeholders before committing.

### No `.htaccess` or rewrite rules
Despite `a2enmod rewrite` in the Dockerfile, there are no `.htaccess` files. All URLs are direct `.php` file paths (e.g., `/index_compsp.php`).

### Form flow pattern
Each tool follows the same pattern: an `index_*.php` form posts to a handler `*.php` which does the work and renders results inline. Forms use `POST` with server selection dropdowns populated from `servidores.xml`.
