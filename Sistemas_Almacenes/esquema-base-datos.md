# Esquema de Base de Datos — Sistemas Almacenes

---

## users
| Columna    | Tipo         | Restricciones                  |
|------------|--------------|-------------------------------|
| id         | bigint       | PK, autoincremental            |
| nombre     | string       | not null                       |
| apellido   | string       | not null                       |
| rut        | string       | unique, not null               |
| cargo      | enum         | 'Dueño' \| 'Vendedor', not null |
| email      | string       | unique, not null               |
| password   | string       | not null (encriptado)          |
| created_at | timestamp    |                                |
| updated_at | timestamp    |                                |

---

## categorias
| Columna    | Tipo      | Restricciones        |
|------------|-----------|----------------------|
| id         | bigint    | PK, autoincremental  |
| nombre     | string    | unique, not null     |
| activo     | boolean   | default true         |
| created_at | timestamp |                      |
| updated_at | timestamp |                      |

---

## productos
| Columna         | Tipo      | Restricciones                       |
|-----------------|-----------|-------------------------------------|
| id              | bigint    | PK, autoincremental                 |
| sku             | string    | unique, not null                    |
| codigo_barras   | string    | unique, nullable                    |
| nombre          | string    | not null                            |
| descripcion     | text      | nullable                            |
| categoria_id    | bigint    | FK → categorias.id, not null        |
| precio_venta    | integer   | >= 0, not null (CLP)                |
| costo           | integer   | >= 0, not null (CLP)                |
| unidad          | string    | not null (ej: "unidad", "kg")       |
| stock_minimo    | integer   | >= 0, default 0                     |
| cantidad_actual | integer   | puede ser negativo según config     |
| activo          | boolean   | default true                        |
| created_at      | timestamp |                                     |
| updated_at      | timestamp |                                     |

---

## movimientos
| Columna         | Tipo      | Restricciones                              |
|-----------------|-----------|--------------------------------------------|
| id              | bigint    | PK, autoincremental                        |
| producto_id     | bigint    | FK → productos.id, not null                |
| usuario_id      | bigint    | FK → users.id, not null                    |
| tipo_movimiento | enum      | 'entrada' \| 'salida' \| 'ajuste', not null |
| cantidad        | integer   | distinto de 0, not null                    |
| motivo          | string    | not null                                   |
| created_at      | timestamp |                                            |
| updated_at      | timestamp |                                            |

---

## ventas
| Columna              | Tipo      | Restricciones                                         |
|----------------------|-----------|-------------------------------------------------------|
| id                   | bigint    | PK, autoincremental                                   |
| usuario_id           | bigint    | FK → users.id, not null                               |
| medio_pago           | enum      | 'Efectivo' \| 'Débito' \| 'Crédito' \| 'Transferencia' |
| monto_recibido       | integer   | nullable (obligatorio solo si medio_pago = Efectivo)  |
| estado               | enum      | 'Completada' \| 'Anulada', default 'Completada'       |
| motivo_anulacion     | string    | nullable                                              |
| usuario_anulacion_id | bigint    | FK → users.id, nullable                               |
| created_at           | timestamp |                                                       |
| updated_at           | timestamp |                                                       |

---

## detalle_venta
| Columna         | Tipo      | Restricciones               |
|-----------------|-----------|-----------------------------|
| id              | bigint    | PK, autoincremental         |
| venta_id        | bigint    | FK → ventas.id, not null    |
| producto_id     | bigint    | FK → productos.id, not null |
| cantidad        | integer   | > 0, not null               |
| precio_unitario | integer   | >= 0, not null (CLP)        |
| created_at      | timestamp |                             |
| updated_at      | timestamp |                             |

---

## proveedores
| Columna    | Tipo      | Restricciones       |
|------------|-----------|---------------------|
| id         | bigint    | PK, autoincremental |
| nombre     | string    | not null            |
| contacto   | string    | nullable            |
| activo     | boolean   | default true        |
| created_at | timestamp |                     |
| updated_at | timestamp |                     |

---

## compras
| Columna      | Tipo      | Restricciones                  |
|--------------|-----------|--------------------------------|
| id           | bigint    | PK, autoincremental            |
| proveedor_id | bigint    | FK → proveedores.id, not null  |
| usuario_id   | bigint    | FK → users.id, not null        |
| created_at   | timestamp |                                |
| updated_at   | timestamp |                                |

---

## detalle_compra
| Columna        | Tipo      | Restricciones               |
|----------------|-----------|-----------------------------|
| id             | bigint    | PK, autoincremental         |
| compra_id      | bigint    | FK → compras.id, not null   |
| producto_id    | bigint    | FK → productos.id, not null |
| cantidad       | integer   | > 0, not null               |
| costo_unitario | integer   | >= 0, not null (CLP)        |
| created_at     | timestamp |                             |
| updated_at     | timestamp |                             |

---

## Relaciones clave

- `productos` → `categorias` (muchos a uno)
- `movimientos` → `productos`, `users` (muchos a uno)
- `ventas` → `users` (muchos a uno)
- `detalle_venta` → `ventas`, `productos` (muchos a uno)
- `compras` → `proveedores`, `users` (muchos a uno)
- `detalle_compra` → `compras`, `productos` (muchos a uno)

## Reglas de negocio importantes

- `cantidad_actual` en `productos` **nunca se modifica directamente** — toda variación pasa por un registro en `movimientos`.
- `precio_unitario` en `detalle_venta` se captura al momento de la venta y no cambia aunque el precio del producto cambie después.
- Las tablas con `activo` usan **soft delete lógico** — no se eliminan físicamente.
- Al anular una venta, se crean movimientos de entrada para revertir las salidas originales.
