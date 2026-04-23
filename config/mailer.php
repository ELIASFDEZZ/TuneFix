<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

// ─── CONFIGURA AQUÍ TUS CREDENCIALES ────────────────────────────────────────
define('MAIL_FROM',     'alejandrotaguaaguilar2006@gmail.com');   // tu Gmail
define('MAIL_FROM_NAME','TuneFix');
define('MAIL_PASSWORD', 'zqgo esde zuvq jghu');  // contraseña de aplicación Gmail (16 chars)
// ─────────────────────────────────────────────────────────────────────────────

// URL base de la app (sin barra final)
define('APP_URL', 'http://localhost/DWES/Repositorio/TuneFix');

/**
 * Envía el email de verificación al usuario recién registrado.
 * Devuelve true si se envió correctamente, false si hubo error.
 */
function enviarEmailVerificacion(string $destinatario, string $nombre, string $token): bool
{
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_FROM;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Remitente y destinatario
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($destinatario, $nombre);

        // Contenido
        $enlace = APP_URL . '/verificar.php?token=' . urlencode($token);
        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta profesional en TuneFix';
        $mail->Body    = emailBodyVerificacion($nombre, $enlace);
        $mail->AltBody = "Hola $nombre, haz clic en este enlace para verificar tu cuenta: $enlace";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer error: ' . $mail->ErrorInfo);
        return false;
    }
}

function emailBodyVerificacion(string $nombre, string $enlace): string
{
    return <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;background:#0f0f0f;font-family:Arial,sans-serif;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0f0f;padding:40px 0;">
        <tr><td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1a1a;border-radius:12px;overflow:hidden;">
            <!-- Cabecera -->
            <tr>
              <td style="background:linear-gradient(135deg,#ff6b35,#e63946);padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:28px;letter-spacing:2px;">TUNE<span style="color:#0f0f0f;">FIX</span></h1>
                <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:14px;">Cuenta Profesional</p>
              </td>
            </tr>
            <!-- Cuerpo -->
            <tr>
              <td style="padding:40px 36px;color:#e0e0e0;">
                <p style="font-size:18px;margin:0 0 16px;">Hola, <strong style="color:#ff6b35;">$nombre</strong></p>
                <p style="font-size:15px;line-height:1.6;margin:0 0 24px;color:#aaa;">
                  Te has registrado como <strong style="color:#fff;">profesional</strong> en TuneFix. Para activar tu cuenta y acceder a todas las funciones, haz clic en el botón de abajo.
                </p>
                <div style="text-align:center;margin:32px 0;">
                  <a href="$enlace"
                     style="background:#ff6b35;color:#fff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:16px;font-weight:bold;display:inline-block;">
                    Verificar mi cuenta
                  </a>
                </div>
                <p style="font-size:13px;color:#666;margin:24px 0 0;text-align:center;">
                  Si no te has registrado en TuneFix, ignora este correo.
                </p>
              </td>
            </tr>
            <!-- Pie -->
            <tr>
              <td style="background:#111;padding:20px;text-align:center;">
                <p style="color:#444;font-size:12px;margin:0;">© 2026 TuneFix · Todos los derechos reservados</p>
              </td>
            </tr>
          </table>
        </td></tr>
      </table>
    </body>
    </html>
    HTML;
}
