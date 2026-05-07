# PROMPT: Sistema de Carrito + Pasarela de Pago Stripe para TuneFix

## Contexto del Proyecto

**TuneFix** es una aplicación web en **PHP puro con patrón MVC**, stack XAMPP, MariaDB y Bootstrap 5.3.3.

- **Entry points**: `pieza-detalle.php`, `todas-piezas.php`, `carrito.php` (nuevo), `checkout.php` (nuevo)
- **Patrón MVC**: Los controllers están en `/controllers/`, los modelos en `/models/`, las vistas en `/views/`
- **Sesiones**: Se usa `$_SESSION['usuario_id']`, `$_SESSION['usuario_nombre']`, `$_SESSION['usuario_rol']`
- **BD**: PDO via `config/Database.php` (clase singleton `Database::getConnection()`)
- **Estilos**: Bootstrap 5.3.3 + Font Awesome 6.5.0. Color corporativo principal: `rgb(164, 4, 46)`
- **Header compartido**: `views/layouts/header.php` — contiene la navbar fija con menú y dropdown de usuario

---

## Estructura de la Tabla `pieza` (ya existente en BD)

```sql
CREATE TABLE `pieza` (
  `id`           int(11) NOT NULL AUTO_INCREMENT,
  `referencia`   varchar(50) NOT NULL,
  `nombre`       varchar(100) NOT NULL,
  `descripcion`  text DEFAULT NULL,
  `imagen`       varchar(255) NOT NULL,
  `url`          varchar(255) DEFAULT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `categoria`    varchar(100) DEFAULT NULL,
  `estado_pieza` enum('nueva','usada_buena','usada_desgaste') DEFAULT 'nueva',
  `precio`       decimal(10,2) DEFAULT 0.00,
  `stock`        int(11) DEFAULT 0,
  `garantia`     varchar(50) DEFAULT 'Sin garantía',
  `activa`       tinyint(1) DEFAULT 1,
  `created_at`   timestamp NOT NULL DEFAULT current_timestamp()
);
```

> ⚠️ La tabla ya tiene `precio` y `stock`. El modelo `PiezaModel` NO los devuelve todavía — hay que actualizar las queries.

---

## Tablas nuevas a crear en MySQL

Ejecutar este SQL en la BD `tunefix`:

```sql
-- Tabla de pedidos
CREATE TABLE IF NOT EXISTS `pedido` (
  `id`                int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id`        int(11) DEFAULT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `stripe_payment_intent` varchar(255) DEFAULT NULL,
  `estado`            enum('pendiente','pagado','cancelado') DEFAULT 'pendiente',
  `total`             decimal(10,2) NOT NULL DEFAULT 0.00,
  `nombre_cliente`    varchar(100) DEFAULT NULL,
  `email_cliente`     varchar(150) DEFAULT NULL,
  `created_at`        timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_pedido_usuario` (`usuario_id`),
  CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Líneas de cada pedido
CREATE TABLE IF NOT EXISTS `pedido_item` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id`  int(11) NOT NULL,
  `pieza_id`   int(11) NOT NULL,
  `nombre`     varchar(150) NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `precio`     decimal(10,2) NOT NULL,
  `cantidad`   int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_item_pedido` (`pedido_id`),
  KEY `fk_item_pieza`  (`pieza_id`),
  CONSTRAINT `fk_item_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_pieza`  FOREIGN KEY (`pieza_id`)  REFERENCES `pieza`  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Instalación de Stripe SDK

Ejecutar en la raíz del proyecto (`C:\xampp\htdocs\Practicas\prueba\TuneFix`):

```bash
composer require stripe/stripe-php
```

---

## Variables de Entorno / Configuración

Crear el archivo `config/stripe.php`:

```php
<?php
// Modo TEST — reemplazar con claves reales cuando se vaya a producción
define('STRIPE_SECRET_KEY',      'sk_test_XXXXXXXXXXXXXXXXXXXXXXXXXX');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_XXXXXXXXXXXXXXXXXXXXXXXXXX');
define('STRIPE_WEBHOOK_SECRET',  'whsec_XXXXXXXXXXXXXXXXXXXXXXXXXX'); // opcional para demo
define('APP_URL', 'http://localhost/Practicas/prueba/TuneFix');       // URL base del proyecto
```

> Las claves de test se obtienen en https://dashboard.stripe.com/test/apikeys

---

## Archivos a Crear / Modificar

### 1. Actualizar `models/PiezaModel.php`

En todos los métodos `getById`, `getAll`, `getAllByMotorizacion`, `getRecientes` y `getByMotorizacion`, añadir los campos `precio`, `stock`, `estado_pieza`, `garantia` en los SELECT. Por ejemplo, `getById` debe quedar:

```sql
SELECT id, referencia, nombre, descripcion, imagen, precio, stock, estado_pieza, garantia
FROM pieza
WHERE id = ? AND activa = 1
```

---

### 2. Crear `models/CarritoModel.php`

El carrito se gestiona **100% en sesión PHP** (no hay tabla de carrito en BD). Crear la clase con los métodos:

- `agregar(array $pieza, int $cantidad = 1): void` — si ya existe el item, incrementa cantidad (respetando stock)
- `obtener(): array` — devuelve el array `$_SESSION['carrito']` o `[]`
- `actualizar(int $piezaId, int $cantidad): void` — cambia la cantidad; si `$cantidad <= 0` elimina
- `eliminar(int $piezaId): void`
- `vaciar(): void`
- `total(): float` — suma `precio * cantidad` de todos los items
- `contarItems(): int` — suma de cantidades para el badge del navbar

Estructura de cada item en `$_SESSION['carrito']`:
```php
[
  'id'          => int,
  'nombre'      => string,
  'referencia'  => string,
  'imagen'      => string,
  'precio'      => float,
  'stock'       => int,
  'cantidad'    => int,
]
```

---

### 3. Crear `controllers/CarritoController.php`

Con los métodos:
- `index()` — carga `views/carrito/index.php` con los datos del carrito
- `agregar()` — recibe POST `pieza_id` + `cantidad`, llama a `CarritoModel::agregar()`, redirige
- `actualizar()` — recibe POST `pieza_id` + `cantidad`, llama a `CarritoModel::actualizar()`
- `eliminar()` — recibe POST `pieza_id`, llama a `CarritoModel::eliminar()`
- `vaciar()` — llama a `CarritoModel::vaciar()`

---

### 4. Crear `carrito.php` (entry point)

Igual que el resto de entry points del proyecto (ej. `pieza-detalle.php`):

```php
<?php
session_start();
require_once 'controllers/CarritoController.php';
$controller = new CarritoController();

$accion = $_GET['accion'] ?? 'index';
match ($accion) {
    'agregar'    => $controller->agregar(),
    'actualizar' => $controller->actualizar(),
    'eliminar'   => $controller->eliminar(),
    'vaciar'     => $controller->vaciar(),
    default      => $controller->index(),
};
```

---

### 5. Crear `views/carrito/index.php`

Vista de carrito con el estilo visual de TuneFix (fondo oscuro, colores `rgb(164,4,46)`, Bootstrap 5):

**Sección HERO** (igual que el resto de páginas):
```html
<section class="page-hero text-white">
  <div class="container">
    <h1><i class="fas fa-shopping-cart me-2" style="color:rgba(255,60,0,0.9)"></i> Mi carrito</h1>
  </div>
</section>
```

**Tabla de items** (fondo blanco, `.content-section`):
- Columnas: Imagen | Pieza | Referencia | Precio unit. | Cantidad (input number) | Subtotal | Eliminar
- Fila de totales abajo
- Botón **"Actualizar carrito"** (PATCH via form POST)
- Botón **"Vaciar carrito"**
- Botón grande **"Proceder al pago"** → redirige a `checkout.php`
- Si el carrito está vacío, mostrar mensaje y enlace a `todas-piezas.php`

**IMPORTANTE**: El botón de actualizar cantidad debe funcionar con un formulario clásico (no AJAX) para máxima compatibilidad.

---

### 6. Modificar `views/piezas/detalle.php`

En la sección de botones (línea ~113, después del botón "Me gusta"), añadir:

```html
<!-- Sección de compra -->
<?php if ($pieza['stock'] > 0): ?>
  <div class="mt-4 p-4 rounded-3" style="background:rgba(164,4,46,0.05); border:1px solid rgba(164,4,46,0.2);">

    <!-- Precio y estado -->
    <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
      <span class="fw-bold" style="font-size:1.8rem; color:rgb(164,4,46);">
        <?= number_format($pieza['precio'], 2, ',', '.') ?> €
      </span>
      <span class="badge" style="background:rgba(0,180,100,0.15); color:#00b464; border:1px solid #00b464; border-radius:50px; padding:5px 14px;">
        <i class="fas fa-check-circle me-1"></i>En stock (<?= (int)$pieza['stock'] ?> uds.)
      </span>
      <?php if (!empty($pieza['garantia']) && $pieza['garantia'] !== 'Sin garantía'): ?>
        <span class="badge bg-secondary rounded-pill">
          <i class="fas fa-shield-alt me-1"></i><?= htmlspecialchars($pieza['garantia']) ?>
        </span>
      <?php endif; ?>
    </div>

    <!-- Selector de cantidad -->
    <form action="carrito.php?accion=agregar" method="POST" class="d-flex align-items-center gap-3 flex-wrap">
      <input type="hidden" name="pieza_id" value="<?= (int)$pieza['id'] ?>">
      <div class="d-flex align-items-center gap-2">
        <label class="fw-semibold text-dark mb-0">Cantidad:</label>
        <input type="number" name="cantidad" value="1" min="1" max="<?= (int)$pieza['stock'] ?>"
               class="form-control text-center fw-bold"
               style="width:80px; border:1.5px solid rgb(164,4,46); border-radius:8px;">
      </div>

      <!-- Añadir al carrito -->
      <button type="submit" class="btn d-flex align-items-center gap-2 fw-bold px-4 py-2"
              style="background:rgb(164,4,46); color:#fff; border-radius:50px; border:none; font-size:.95rem;">
        <i class="fas fa-cart-plus"></i> Añadir al carrito
      </button>

      <!-- Comprar ahora -->
      <button type="submit" name="comprar_ahora" value="1"
              class="btn d-flex align-items-center gap-2 fw-bold px-4 py-2"
              style="background:#fff; color:rgb(164,4,46); border:2px solid rgb(164,4,46); border-radius:50px; font-size:.95rem;">
        <i class="fas fa-bolt"></i> Comprar ahora
      </button>
    </form>
  </div>
<?php else: ?>
  <div class="mt-4 alert" style="background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.3); border-radius:12px; color:#dc3545;">
    <i class="fas fa-times-circle me-2"></i><strong>Sin stock</strong> — Este artículo no está disponible actualmente.
  </div>
<?php endif; ?>
```

**Modificar `controllers/PiezaDetalleController.php`**: en el método `index()`, cuando se llame a `PiezaModel::getById()`, el array `$pieza` ya devolverá `precio`, `stock`, `estado_pieza` y `garantia` (tras actualizar el modelo). Simplemente pasarlos al `$data`.

Además, en `CarritoController::agregar()`, si viene `$_POST['comprar_ahora']`, tras añadir al carrito redirigir directamente a `checkout.php` en lugar de al carrito.

---

### 7. Modificar `views/layouts/header.php`

Añadir el **icono de carrito con badge de cantidad** en la navbar, justo antes del bloque `<?php if (isset($_SESSION['usuario_id'])): ?>`:

```php
<?php
  // Contar items del carrito
  $carritoItems = 0;
  if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
      $carritoItems += $item['cantidad'];
    }
  }
?>
<a href="carrito.php" class="btn position-relative me-2"
   style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; border-radius:50px; padding:7px 16px; font-weight:600;">
  <i class="fas fa-shopping-cart"></i>
  <?php if ($carritoItems > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
          style="background:rgb(255,60,0); font-size:0.65rem;">
      <?= $carritoItems ?>
    </span>
  <?php endif; ?>
</a>
```

---

### 8. Modificar `views/piezas/index.php` (listado de piezas)

En cada tarjeta de pieza (`.card-pieza`), añadir debajo del nombre/referencia:

```html
<!-- Precio -->
<p class="fw-bold mb-2" style="color:rgb(164,4,46); font-size:1.1rem;">
  <?= $pieza['precio'] > 0 ? number_format($pieza['precio'], 2, ',', '.') . ' €' : 'Consultar precio' ?>
</p>
<!-- Botón añadir al carrito -->
<?php if ($pieza['stock'] > 0): ?>
  <form action="carrito.php?accion=agregar" method="POST" class="mt-auto">
    <input type="hidden" name="pieza_id" value="<?= (int)$pieza['id'] ?>">
    <input type="hidden" name="cantidad" value="1">
    <button type="submit" class="btn btn-sm w-100 fw-bold"
            style="background:rgb(164,4,46); color:#fff; border-radius:50px; border:none;">
      <i class="fas fa-cart-plus me-1"></i> Añadir al carrito
    </button>
  </form>
<?php else: ?>
  <span class="badge bg-secondary mt-auto">Sin stock</span>
<?php endif; ?>
```

---

### 9. Crear `controllers/CheckoutController.php`

```php
<?php
require_once __DIR__ . '/../config/stripe.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/CarritoModel.php';

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController {

    public function index(): void {
        // Mostrar resumen antes de pagar
        $carrito = new CarritoModel();
        $items   = $carrito->obtener();
        if (empty($items)) {
            header('Location: carrito.php');
            exit;
        }
        $total = $carrito->total();
        extract(['titulo' => 'Checkout — TuneFix', 'items' => $items, 'total' => $total]);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/checkout/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function crearSesionStripe(): void {
        Stripe::setApiKey(STRIPE_SECRET_KEY);

        $carrito   = new CarritoModel();
        $items     = $carrito->obtener();
        if (empty($items)) {
            header('Location: carrito.php'); exit;
        }

        // Construir line_items para Stripe
        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => (int) round($item['precio'] * 100), // céntimos
                    'product_data' => [
                        'name'   => $item['nombre'] . ' (' . $item['referencia'] . ')',
                        'images' => [filter_var($item['imagen'], FILTER_VALIDATE_URL) ? $item['imagen'] : ''],
                    ],
                ],
                'quantity' => $item['cantidad'],
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => APP_URL . '/checkout.php?accion=exito&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => APP_URL . '/checkout.php?accion=cancelado',
            'locale'               => 'es',
        ]);

        // Guardar session_id en PHP session para verificarlo en éxito
        $_SESSION['stripe_session_id'] = $session->id;

        header('Location: ' . $session->url);
        exit;
    }

    public function exito(): void {
        // Verificar la sesión de Stripe
        Stripe::setApiKey(STRIPE_SECRET_KEY);
        $sessionId = $_GET['session_id'] ?? '';

        try {
            $stripeSession = StripeSession::retrieve($sessionId);
        } catch (\Exception $e) {
            header('Location: index.php'); exit;
        }

        if ($stripeSession->payment_status === 'paid') {
            // Guardar pedido en BD
            $carrito = new CarritoModel();
            $this->guardarPedido($stripeSession, $carrito->obtener(), $carrito->total());
            $carrito->vaciar();
        }

        $titulo = 'Pago completado — TuneFix';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/checkout/exito.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function cancelado(): void {
        $titulo = 'Pago cancelado — TuneFix';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/checkout/cancelado.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    private function guardarPedido(\Stripe\Checkout\Session $stripeSession, array $items, float $total): void {
        require_once __DIR__ . '/../config/Database.php';
        $pdo = Database::getConnection();

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $stmt = $pdo->prepare(
            "INSERT INTO pedido (usuario_id, stripe_session_id, stripe_payment_intent, estado, total, nombre_cliente, email_cliente)
             VALUES (?, ?, ?, 'pagado', ?, ?, ?)"
        );
        $stmt->execute([
            $usuarioId,
            $stripeSession->id,
            $stripeSession->payment_intent,
            $total,
            $stripeSession->customer_details->name ?? null,
            $stripeSession->customer_details->email ?? null,
        ]);
        $pedidoId = (int) $pdo->lastInsertId();

        $stmtItem = $pdo->prepare(
            "INSERT INTO pedido_item (pedido_id, pieza_id, nombre, referencia, precio, cantidad)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        foreach ($items as $item) {
            $stmtItem->execute([$pedidoId, $item['id'], $item['nombre'], $item['referencia'], $item['precio'], $item['cantidad']]);
        }
    }
}
```

---

### 10. Crear `checkout.php` (entry point)

```php
<?php
session_start();
require_once 'controllers/CheckoutController.php';
$controller = new CheckoutController();

$accion = $_GET['accion'] ?? 'index';
match ($accion) {
    'pagar'     => $controller->crearSesionStripe(),
    'exito'     => $controller->exito(),
    'cancelado' => $controller->cancelado(),
    default     => $controller->index(),
};
```

---

### 11. Crear `views/checkout/index.php`

Resumen del pedido antes de ir a Stripe. Debe mostrar:
- Tabla con los items (nombre, cantidad, precio, subtotal)
- Total final en grande
- Aviso de que el pago es simulado con tarjeta de test Stripe: `4242 4242 4242 4242`
- Botón **"Pagar con Stripe"** → `<form action="checkout.php?accion=pagar" method="POST"><button type="submit">...</button></form>`
- Botón secundario "Volver al carrito"
- Logo de Stripe y texto "Pago seguro con Stripe"

Usar el mismo estilo visual de TuneFix (fondo oscuro, `.content-section`, colores corporativos).

---

### 12. Crear `views/checkout/exito.php`

Página de éxito de pago:
- Icono grande de check verde
- Título: "¡Pago completado con éxito!"
- Subtexto: "Tu pedido ha sido registrado. Recibirás más información en breve."
- Botón "Seguir comprando" → `todas-piezas.php`
- Botón "Ir al inicio" → `index.php`

---

### 13. Crear `views/checkout/cancelado.php`

Página de pago cancelado:
- Icono de X roja
- Título: "Pago cancelado"
- Subtexto: "No se realizó ningún cargo. Puedes volver a intentarlo."
- Botón "Volver al carrito" → `carrito.php`

---

## Flujo completo de usuario

```
[Página pieza detalle]
       ↓ "Añadir al carrito" (POST → carrito.php?accion=agregar)
[Carrito] → muestra items, totales, opción de editar cantidad / eliminar
       ↓ "Proceder al pago"
[Checkout resumen] → muestra pedido + aviso tarjeta test
       ↓ "Pagar con Stripe" (POST → checkout.php?accion=pagar)
[Stripe Checkout] (hosted page de Stripe)
       ↓ éxito / cancelado
[checkout.php?accion=exito] → guarda pedido en BD → vacía carrito → muestra confirmación
[checkout.php?accion=cancelado] → muestra mensaje → volver al carrito
```

---

## Tarjeta de prueba Stripe (modo test)

| Campo       | Valor                  |
|-------------|------------------------|
| Número      | `4242 4242 4242 4242`  |
| Vencimiento | Cualquier fecha futura |
| CVC         | Cualquier 3 dígitos    |
| CP          | Cualquier código       |

---

## Consideraciones importantes

1. **`session_start()`** debe llamarse al inicio de cada entry point (`carrito.php`, `checkout.php`). Verificar que `pieza-detalle.php` también lo tenga.
2. **Imágenes**: en `views/piezas/index.php`, el query de `PiezaModel::getAll()` debe incluir también `precio` y `stock`.
3. **Seguridad CSRF básica**: en los formularios del carrito, añadir un token CSRF en sesión y validarlo en el controller.
4. **Stock**: el método `CarritoModel::agregar()` debe verificar que `cantidad <= $pieza['stock']` antes de añadir.
5. **Sin login obligatorio**: el carrito funciona sin estar logueado (solo sesión PHP). Si el usuario no está logueado, Stripe igualmente pedirá el email al finalizar el pago.
6. **Modo simulación**: este es un entorno de demo (XAMPP local). Stripe en modo test no cobra nada real.
7. **Precios en BD**: algunos registros de `pieza` pueden tener `precio = 0.00`. Mostrar "Consultar precio" en esos casos y deshabilitar el botón de compra.
