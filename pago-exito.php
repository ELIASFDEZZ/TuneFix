<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php'); exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/stripe.php';
require_once __DIR__ . '/models/CarritoModel.php';
require_once __DIR__ . '/config/Database.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$sessionId = trim($_GET['session_id'] ?? '');
$usuarioId = (int) $_SESSION['usuario_id'];
$pagoCorrecto = false;

if ($sessionId !== '') {
    try {
        $stripeSession = \Stripe\Checkout\Session::retrieve($sessionId);

        if ($stripeSession->payment_status === 'paid') {
            $pdo  = Database::getConnection();
            $stmt = $pdo->prepare(
                "UPDATE pedido SET estado = 'pagado' WHERE stripe_session_id = ? AND usuario_id = ?"
            );
            $stmt->execute([$sessionId, $usuarioId]);

            $carritoModel = new CarritoModel();
            $carritoModel->clear($usuarioId);

            $pagoCorrecto = true;
        }
    } catch (\Exception $e) {
        // Silencioso: se mostrará el estado por defecto
    }
}

$titulo = '¡Pago completado! — TuneFix';
require_once __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/views/pago/exito.php';
require_once __DIR__ . '/views/layouts/footer.php';
