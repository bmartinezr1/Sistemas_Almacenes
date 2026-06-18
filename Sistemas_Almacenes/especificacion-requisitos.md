

## Índice

- [Requisitos Funcionales](#requisitos-funcionales)
  - [R1. Registro de usuario](#r1-registro-de-usuario)
  - [R2. Inicio de sesión](#r2-inicio-de-sesión)
  - [R3. Gestión de categorías](#r3-gestión-de-categorías)
  - [R4. Gestión de productos](#r4-gestión-de-productos)
  - [R5. Consulta de inventario y alerta de stock](#r5-consulta-de-inventario-y-alerta-de-stock)
  - [R6. Ajuste manual de stock](#r6-ajuste-manual-de-stock)
  - [R7. Registro de venta](#r7-registro-de-venta)
  - [R8. Anulación de venta](#r8-anulación-de-venta)
  - [R9. Gestión de proveedores](#r9-gestión-de-proveedores)
  - [R10. Registro de compra (reposición de stock)](#r10-registro-de-compra-reposición-de-stock)
  - [R11. Reportes de ventas e inventario](#r11-reportes-de-ventas-e-inventario)

---

# Requisitos Funcionales

## R1. Registro de usuario

**Entrada:** nombre, apellido, rut, cargo, email, password

**Requisitos:**

- **R1.1.** Para nombre se utilizará un string, no puede ser nulo.
- **R1.2.** Para apellido se utilizará un string, no puede ser nulo.
- **R1.3.** Para cargo se utilizará un string limitado a los valores "Dueño" o "Vendedor"; no puede ser nulo.
- **R1.4.** Para rut se utilizará un string que puede incluir puntos y guión; debe ser único y no puede ser nulo.
- **R1.5.** Para email se utilizará un string de formato estándar (ejemplo example@gmail.com), debe incluir "@" y "."; debe ser único y no puede ser nulo.
- **R1.6.** Para password se utilizará un string con un mínimo de 8 caracteres; no puede ser nulo.
- **R1.7.** El sistema debe desplegar un formulario de registro solicitando los datos mencionados (R1.1 a R1.6) y requerir el ingreso de la contraseña en dos campos (Password y confirmación de Password), validando que ambos coincidan. Si alguno de los datos no es válido (campos vacíos, formato incorrecto o contraseñas que no coinciden), el sistema mostrará mensajes de error específicos debajo de cada campo, indicando la regla que no se cumple, y solicitará al usuario corregir la información.
- **R1.8.** Si el sistema detecta que el email o el rut ingresado ya existen en la tabla "users", desplegará el mensaje de error correspondiente (ejemplo "El RUT ya se encuentra registrado.") y denegará la creación.
- **R1.9.** Tras verificar y cumplir todos los requisitos anteriores, el sistema deberá encriptar el campo password, registrar los datos en la base de datos, generar una sesión autenticada y redirigir al usuario a la vista principal (Dashboard).

**Salida 1:** Creación del registro en la tabla users con los datos proporcionados y la contraseña encriptada.

**Salida 2:** Despliegue de los mensajes de error R1.7 y R1.8.

---

## R2. Inicio de sesión

**Entrada:** email, password

**Requisitos:**

- **R2.1.** Para email se utilizará un string de formato estándar (ejemplo example@gmail.com), debe incluir "@" y "."; no puede ser nulo.
- **R2.2.** Para password se utilizará un string con un mínimo de 8 caracteres; no puede ser nulo.
- **R2.3.** Los dos requisitos anteriores son obligatorios.
- **R2.4.** El sistema debe desplegar un formulario solicitando las credenciales de acceso email y password.
- **R2.5.** Al enviar el formulario, el sistema validará que los campos no estén vacíos y que el email tenga el formato correcto según los requisitos R2.1 y R2.2. Si no cumplen, desplegará mensajes de error de validación y detendrá el proceso.
- **R2.6.** El sistema debe verificar que el usuario no esté bloqueado por límite de intentos. Se permite un máximo de 5 intentos fallidos por minuto asociados a una misma dirección IP o email.
- **R2.7.** Si el usuario excede los 5 intentos fallidos, el sistema bloqueará temporalmente el acceso y desplegará el mensaje: "Demasiados intentos de acceso. Por favor intente nuevamente".
- **R2.8.** Si no está bloqueado, el sistema comparará las credenciales ingresadas con los registros de la base de datos.
- **R2.9.** Si las credenciales no coinciden, el sistema registrará un intento fallido y desplegará el mensaje: "Estas credenciales no coinciden".
- **R2.10.** Si las credenciales son correctas, el sistema limpiará el contador de intentos fallidos, regenerará la sesión por seguridad y autorizará el acceso.

**Salida 1:** Despliegue de los mensajes R2.5, R2.7 y R2.9.

**Salida 2:** Redirección a la pestaña principal.

---

## R3. Gestión de categorías

**Entrada:** categoria_id, nombre

**Requisitos:**

- **R3.1.** Para categoria_id se utilizará un entero autoincremental.
- **R3.2.** Para nombre se utilizará un string; debe ser único y no puede ser nulo.
- **R3.3.** El sistema debe desplegar una interfaz que liste las categorías existentes y permita crear y editar categorías.
- **R3.4.** Al crear o editar una categoría, el sistema validará que el nombre no esté vacío y que no exista otra categoría con el mismo nombre; en caso contrario desplegará el mensaje "Ya existe una categoría con ese nombre.".
- **R3.5.** El sistema no permitirá la eliminación física de categorías; en su lugar permitirá desactivarlas (soft delete) para preservar el historial de los productos asociados.
- **R3.6.** Una categoría desactivada no estará disponible para asignarse a nuevos productos, pero conservará su vínculo con los productos históricos.

**Salida 1:** Creación o actualización del registro en la tabla categorias.

**Salida 2:** Despliegue del mensaje de validación R3.4.

---

## R4. Gestión de productos

**Entrada:** producto_id, sku, codigo_barras, nombre, descripcion, categoria_id, precio_venta, costo, unidad, stock_minimo, activo

**Requisitos:**

- **R4.1.** Para producto_id se utilizará un entero autoincremental.
- **R4.2.** Para sku se utilizará un string; debe ser único y no puede ser nulo.
- **R4.3.** Para codigo_barras se utilizará un string; cuando se informe debe ser único; puede ser nulo.
- **R4.4.** Para nombre se utilizará un string; no puede ser nulo.
- **R4.5.** Para descripcion se utilizará un string; puede ser nulo.
- **R4.6.** Para categoria_id se utilizará un entero positivo que debe corresponder a una categoría existente y activa.
- **R4.7.** Para precio_venta se utilizará un entero mayor o igual a 0 expresado en pesos chilenos (CLP, sin decimales); no puede ser nulo.
- **R4.8.** Para costo se utilizará un entero mayor o igual a 0 expresado en CLP; no puede ser nulo.
- **R4.9.** Para unidad se utilizará un string que represente la unidad de medida (ejemplo "unidad", "kilogramo", "litro").
- **R4.10.** Para stock_minimo se utilizará un entero mayor o igual a 0.
- **R4.11.** Para activo se utilizará un valor booleano que indica si el producto está disponible para la venta.
- **R4.12.** El sistema debe desplegar un formulario para crear y editar productos solicitando los campos R4.2 a R4.11. Si algún dato no es válido, desplegará mensajes de error específicos debajo de cada campo.
- **R4.13.** El sistema validará que sku y codigo_barras sean únicos; si ya existen, desplegará el mensaje correspondiente (ejemplo "El SKU ya se encuentra registrado.") y denegará la operación.
- **R4.14.** El sistema debe desplegar un listado de productos con búsqueda por nombre, sku o codigo_barras y filtros por categoría y estado (activo/inactivo).
- **R4.15.** El sistema no permitirá la eliminación física de productos; en su lugar permitirá desactivarlos (soft delete) para preservar el historial de ventas y movimientos.

**Salida 1:** Creación o actualización del registro en la tabla productos.

**Salida 2:** Despliegue de los mensajes de error R4.12 y R4.13.

---

## R5. Consulta de inventario y alerta de stock

**Entrada:** producto_id, cantidad_actual, stock_minimo

**Requisitos:**

- **R5.1.** Para cantidad_actual se utilizará un entero que representa el stock disponible del producto; podrá ser negativo solo si la configuración del sistema permite la venta sin stock.
- **R5.2.** Para stock_minimo se utilizará un entero mayor o igual a 0.
- **R5.3.** El sistema debe desplegar una vista de inventario que liste los productos activos junto con su cantidad_actual y su stock_minimo.
- **R5.4.** La vista de inventario debe permitir búsqueda por nombre, sku o codigo_barras y filtro por categoría.
- **R5.5.** El sistema debe destacar visualmente los productos cuya cantidad_actual sea menor o igual a su stock_minimo, indicando la condición de stock bajo.
- **R5.6.** El sistema debe permitir desplegar un listado filtrado únicamente con los productos en condición de stock bajo. Si no existen, mostrará el mensaje "No existen productos bajo el stock mínimo".

**Salida 1:** Despliegue de la vista de inventario con la cantidad actual y el indicador de stock bajo.

**Salida 2:** Despliegue del mensaje R5.6.

---

## R6. Ajuste manual de stock

**Entrada:** producto_id, tipo_movimiento, cantidad, motivo, usuario_id

**Requisitos:**

- **R6.1.** Para producto_id se utilizará un entero positivo que debe corresponder a un producto existente.
- **R6.2.** Para tipo_movimiento se utilizará un string limitado a los valores "entrada", "salida" o "ajuste".
- **R6.3.** Para cantidad se utilizará un entero distinto de 0.
- **R6.4.** Para motivo se utilizará un string; no puede ser nulo.
- **R6.5.** Para usuario_id se utilizará el identificador del usuario autenticado que realiza el ajuste.
- **R6.6.** El sistema debe desplegar una interfaz que permita registrar un ajuste de stock indicando el producto, el tipo de movimiento, la cantidad y el motivo.
- **R6.7.** El sistema validará que el motivo no esté vacío; en caso contrario desplegará el mensaje "Debe ingresar un motivo para el ajuste de stock" y detendrá la operación.
- **R6.8.** Al confirmar el ajuste, el sistema deberá, dentro de una única transacción, registrar el movimiento en la tabla movimientos y actualizar el campo cantidad_actual del producto.
- **R6.9.** El sistema no permitirá modificar la cantidad_actual de forma directa; toda variación de stock deberá originarse en un movimiento registrado, garantizando la trazabilidad del inventario.

**Salida 1:** Creación del registro en la tabla movimientos y actualización de cantidad_actual.

**Salida 2:** Despliegue del mensaje de validación R6.7.

---

## R7. Registro de venta

**Entrada:** venta_id, usuario_id, detalle (producto_id, cantidad, precio_unitario), medio_pago, monto_recibido

**Requisitos:**

- **R7.1.** Para venta_id se utilizará un entero autoincremental.
- **R7.2.** Para usuario_id se utilizará el identificador del usuario autenticado que realiza la venta.
- **R7.3.** Para cada línea del detalle, producto_id se utilizará como un entero positivo que debe corresponder a un producto existente y activo.
- **R7.4.** Para cantidad se utilizará un entero mayor que 0.
- **R7.5.** Para precio_unitario se utilizará un entero mayor o igual a 0 expresado en CLP, capturado al momento de la venta; este valor se almacenará en el detalle y no variará si el precio del producto cambia posteriormente.
- **R7.6.** Para medio_pago se utilizará un string limitado a los valores "Efectivo", "Débito", "Crédito" o "Transferencia".
- **R7.7.** Para monto_recibido se utilizará un entero; será obligatorio únicamente cuando medio_pago sea "Efectivo" y deberá ser mayor o igual al total de la venta.
- **R7.8.** El sistema debe permitir agregar productos a la venta mediante búsqueda por nombre o sku, o mediante el escaneo de su código de barras con la cámara del dispositivo.
- **R7.9.** El sistema debe permitir modificar la cantidad de cada línea y eliminar líneas, recalculando en tiempo real el subtotal de cada línea y el total de la venta.
- **R7.10.** Cuando medio_pago sea "Efectivo", el sistema calculará y desplegará el vuelto como la diferencia entre monto_recibido y el total.
- **R7.11.** Antes de confirmar, el sistema validará que exista stock suficiente para cada producto. Si no lo hay, desplegará el mensaje "Stock insuficiente para el producto [nombre]" y, según la configuración del sistema, bloqueará la venta o solicitará confirmación para permitir la venta sin stock.
- **R7.12.** Al confirmar la venta, el sistema deberá, dentro de una única transacción, crear el registro en ventas, crear las líneas en detalle_venta, registrar los movimientos de salida correspondientes en movimientos y actualizar la cantidad_actual de cada producto.
- **R7.13.** Tras registrar la venta, el sistema desplegará un comprobante con el detalle de los productos, el total, el medio de pago y, cuando corresponda, el vuelto, permitiendo su impresión.

**Salida 1:** Creación de los registros en ventas, detalle_venta y movimientos, y actualización de cantidad_actual.

**Salida 2:** Despliegue del comprobante de venta.

**Salida 3:** Despliegue del mensaje de validación R7.11.

---

## R8. Anulación de venta

**Entrada:** venta_id, usuario_id, motivo_anulacion

**Requisitos:**

- **R8.1.** Para venta_id se utilizará un entero positivo que debe corresponder a una venta existente.
- **R8.2.** Para usuario_id se utilizará el identificador del usuario autenticado que realiza la anulación.
- **R8.3.** Para motivo_anulacion se utilizará un string; no puede ser nulo.
- **R8.4.** El sistema solo permitirá anular ventas que no hayan sido anuladas previamente; en caso contrario desplegará el mensaje "La venta ya se encuentra anulada".
- **R8.5.** El sistema validará que se ingrese un motivo de anulación; en caso contrario desplegará el mensaje "Debe ingresar un motivo para anular la venta".
- **R8.6.** Al confirmar la anulación, el sistema deberá, dentro de una única transacción, registrar movimientos de entrada que reviertan las salidas de la venta, actualizar la cantidad_actual de cada producto y marcar la venta con estado "Anulada", registrando el motivo y el usuario.
- **R8.7.** El sistema no eliminará físicamente la venta; esta permanecerá registrada con su estado de anulación para efectos de auditoría.

**Salida 1:** Registro de los movimientos de reversa, actualización de cantidad_actual y cambio de estado de la venta a "Anulada".

**Salida 2:** Despliegue de los mensajes de validación R8.4 y R8.5.

---

## R9. Gestión de proveedores

**Entrada:** proveedor_id, nombre, contacto

**Requisitos:**

- **R9.1.** Para proveedor_id se utilizará un entero autoincremental.
- **R9.2.** Para nombre se utilizará un string; no puede ser nulo.
- **R9.3.** Para contacto se utilizará un string que podrá contener el teléfono o correo del proveedor; puede ser nulo.
- **R9.4.** El sistema debe desplegar una interfaz que liste los proveedores y permita crearlos y editarlos.
- **R9.5.** Al crear o editar un proveedor, el sistema validará que el nombre no esté vacío; en caso contrario desplegará el mensaje de error correspondiente.
- **R9.6.** El sistema no permitirá la eliminación física de proveedores; en su lugar permitirá desactivarlos (soft delete) para preservar el historial de compras asociado.

**Salida 1:** Creación o actualización del registro en la tabla proveedores.

**Salida 2:** Despliegue del mensaje de validación R9.5.

---

## R10. Registro de compra (reposición de stock)

**Entrada:** compra_id, proveedor_id, usuario_id, detalle (producto_id, cantidad, costo_unitario)

**Requisitos:**

- **R10.1.** Para compra_id se utilizará un entero autoincremental.
- **R10.2.** Para proveedor_id se utilizará un entero positivo que debe corresponder a un proveedor existente.
- **R10.3.** Para usuario_id se utilizará el identificador del usuario autenticado que registra la compra.
- **R10.4.** Para cada línea del detalle, producto_id se utilizará como un entero positivo que debe corresponder a un producto existente.
- **R10.5.** Para cantidad se utilizará un entero mayor que 0.
- **R10.6.** Para costo_unitario se utilizará un entero mayor o igual a 0 expresado en CLP.
- **R10.7.** El sistema debe permitir registrar una compra seleccionando el proveedor y agregando las líneas de productos con su cantidad y costo unitario.
- **R10.8.** Al confirmar la compra, el sistema deberá, dentro de una única transacción, crear el registro en compras, crear las líneas en detalle_compra, registrar los movimientos de entrada correspondientes y actualizar la cantidad_actual de cada producto.
- **R10.9.** El sistema permitirá, de manera opcional, actualizar el campo costo del producto con el costo_unitario informado en la compra.
- **R10.10.** El sistema debe desplegar un listado de compras con filtros por fecha y proveedor, permitiendo ver el detalle de cada compra.

**Salida 1:** Creación de los registros en compras, detalle_compra y movimientos, y actualización de cantidad_actual.

**Salida 2:** Despliegue del listado y de la información de la compra registrada.

---

## R11. Reportes de ventas e inventario

**Entrada:** fecha_inicio, fecha_fin

**Requisitos:**

- **R11.1.** Para fecha_inicio y fecha_fin se utilizarán fechas en formato ISO 8601 (YYYY-MM-DD). El sistema deberá validar que fecha_inicio sea menor o igual a fecha_fin.
- **R11.2.** El sistema debe permitir generar un reporte de ventas por período que muestre el total vendido, el número de ventas y el ticket promedio del rango seleccionado.
- **R11.3.** El sistema debe permitir generar un reporte de los productos más vendidos en el período seleccionado, ordenados de mayor a menor cantidad vendida.
- **R11.4.** El sistema debe permitir generar un reporte de los productos en condición de stock bajo a la fecha de consulta.
- **R11.5.** El sistema debe permitir generar un reporte de margen estimado (precio_venta menos costo) por producto y total del período; este reporte solo estará disponible para usuarios con cargo "Dueño".
- **R11.6.** Si no existen ventas en el período seleccionado, el sistema desplegará el mensaje "No se registran ventas en el período seleccionado".

**Salida 1:** Despliegue de los reportes solicitados con sus totalizadores.

**Salida 2:** Despliegue del mensaje R11.6.
