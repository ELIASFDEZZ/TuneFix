<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php'); exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/stripe.php';
require_once __DIR__ . '/models/CarritoModel.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$usuarioId    = (int) $_SESSION['usuario_id'];
$carritoModel = new CarritoModel();
$items        = $carritoModel->getItems($usuarioId);

if (empty($items)) {
    header('Location: carrito.php'); exit;
}

// Construir line_items para Stripe
$lineItems = [];
foreach ($items as $item) {
    $images = [];
    if (!empty($item['imagen']) && filter_var($item['imagen'], FILTER_VALIDATE_URL)) {
        $images[] = $item['imagen'];
    }
    $lineItems[] = [
        'price_data' => [
            'currency'     => 'eur',
            'product_data' => [
                'name'   => $item['nombre'] . ' (' . $item['referencia'] . ')',
                'images' => $images,
            ],
            'unit_amount' => (int) round((float)$item['precio_u'] * 100),
        ],
        'quantity' => (int) $item['cantidad'],
    ];
}

$baseUrl = 'http://localhost/Practicas/prueba/TuneFix';

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items'           => $lineItems,
        'mode'                 => 'payment',
        'success_url'          => $baseUrl . '/pago-exito.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'           => $baseUrl . '/carrito.php?cancelado=1',
        'locale'               => 'es',
    ]);

    // Guardar pedido en estado pendiente
    require_once __DIR__ . '/config/Database.php';
    $pdo   = Database::getConnection();
    $total = $carritoModel->getTotal($usuarioId);

    $stmt = $pdo->prepare(
        "INSERT INTO pedido (usuario_id, stripe_session_id, total, estado) VALUES (?, ?, ?, 'pendiente')"
    );
    $stmt->execute([$usuarioId, $session->id, $total]);
    $pedidoId = (int) $pdo->lastInsertId();

    $stmtItem = $pdo->prepare(
        "INSERT INTO pedido_item (pedido_id, pieza_id, cantidad, precio_u) VALUES (?, ?, ?, ?)"
    );
    foreach ($items as $item) {
        $stmtItem->execute([$pedidoId, (int)$item['pieza_id'], (int)$item['cantidad'], (float)$item['precio_u']]);
    }

    header('Location: ' . $session->url);
    exit;

} catch (\Stripe\Exception\ApiErrorException $e) {
    $errorMsg = htmlspecialchars($e->getMessage());
    $titulo = 'Error de pago — TuneFix';
    require_once __DIR__ . '/views/layouts/header.php';
    echo '<section class="content-section py-5" style="background:rgba(255,255,255,.95);flex:1;">
            <div class="container text-center py-5">
              <i class="fas fa-exclamation-triangle fa-4x text-danger mb-4 d-block"></i>
              <h3 class="fw-bold text-danger">Error al conectar con Stripe</h3>
              <p class="text-muted mt-3">' . $errorMsg . '</p>
              <p class="text-muted small">Asegúrate de haber configurado las claves de API correctas en <code>config/stripe.php</code>.</p>
              <a href="carrito.php" class="btn mt-3 fw-bold px-4 py-2"
                 style="background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border-radius:50px;">
                <i class="fas fa-arrow-left me-2"></i>Volver al carrito
              </a>
            </div>
          </section>';
    require_once __DIR__ . '/views/layouts/footer.php';
}
