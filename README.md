# MigrarSP

Utilidades de migración para MySQL — comparar stored procedures, extraer tablas referenciadas, generar scripts SQL y comparar/copiar esquemas entre servidores.

## Requisitos

- Docker + Docker Compose

## Uso

```bash
docker-compose up
```

Abrir http://localhost:8080

## Herramientas

| Herramienta | Descripción |
|---|---|
| **Comparar SP** | Muestra qué procedimientos están en un servidor y faltan en otro, con diff lado a lado del código. |
| **Extraer Tablas** | Parsea el código de los stored procedures y extrae las tablas que referencian, comparándolas contra el destino. |
| **Generar Scripts** | Genera scripts SQL con `DROP + CREATE PROCEDURE` listos para migrar. |
| **Copiar Tabla** | Genera `CREATE TABLE` + `INSERT` (incluye datos para tablas de menos de 5000 filas). |
| **Comparar Tabla** | Compara columnas entre servidores y genera `ALTER TABLE`. |

## Configuración de servidores

Los servidores se configuran desde la pestaña **Configuración** en la web, o editando directamente `src/servidores.xml`:

```xml
<configuracion>
  <servidor>
    <nombre>Desarrollo</nombre>
    <host>localhost</host>
    <usuario>root</usuario>
    <clave>password</clave>
    <puerto>3306</puerto>
    <base>mi_base</base>
  </servidor>
</configuracion>
```

> **Importante:** `servidores.xml` contiene contraseñas en texto plano. No subir credenciales reales a repositorios públicos.

## Stack

PHP 8.1 plano (sin framework, sin Composer) + Apache 2 + MySQL vía `mysqli`.
