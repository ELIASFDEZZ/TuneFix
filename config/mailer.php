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

/**
 * Notifica a un seguidor que un profesional ha subido un nuevo tutorial.
 */
function enviarNotificacionNuevoVideo(
    string $destinatario,
    string $nombreDestinatario,
    string $nombreProfesional,
    string $tituloVideo,
    string $youtubeId
): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_FROM;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($destinatario, $nombreDestinatario);

        $enlaceVideo = APP_URL . '/todos-tutoriales.php';
        $thumbnail   = 'https://img.youtube.com/vi/' . urlencode($youtubeId) . '/hqdefault.jpg';

        $mail->isHTML(true);
        $mail->Subject = '📹 ' . $nombreProfesional . ' ha subido un nuevo tutorial en TuneFix';
        $mail->Body    = emailBodyNuevoVideo($nombreDestinatario, $nombreProfesional, $tituloVideo, $thumbnail, $enlaceVideo);
        $mail->AltBody = "Hola $nombreDestinatario, $nombreProfesional ha publicado un nuevo tutorial: \"$tituloVideo\". Míralo en: $enlaceVideo";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer enviarNotificacionNuevoVideo: ' . $mail->ErrorInfo);
        return false;
    }
}

function emailBodyNuevoVideo(
    string $nombreDestinatario,
    string $nombreProfesional,
    string $tituloVideo,
    string $thumbnail,
    string $enlaceVideo
): string {
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
                <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:14px;">Nuevo tutorial disponible</p>
              </td>
            </tr>
            <!-- Cuerpo -->
            <tr>
              <td style="padding:40px 36px;color:#e0e0e0;">
                <p style="font-size:18px;margin:0 0 16px;">Hola, <strong style="color:#ff6b35;">$nombreDestinatario</strong></p>
                <p style="font-size:15px;line-height:1.6;margin:0 0 24px;color:#aaa;">
                  El profesional <strong style="color:#fff;">$nombreProfesional</strong> que sigues acaba de publicar un nuevo tutorial:
                </p>
                <!-- Thumbnail + título -->
                <div style="background:#111;border-radius:10px;overflow:hidden;margin-bottom:28px;">
                  <img src="$thumbnail" alt="Miniatura del vídeo"
                       style="width:100%;display:block;max-height:280px;object-fit:cover;">
                  <div style="padding:16px 20px;">
                    <p style="margin:0;font-size:16px;font-weight:bold;color:#fff;">$tituloVideo</p>
                  </div>
                </div>
                <!-- Botón -->
                <div style="text-align:center;margin:8px 0 32px;">
                  <a href="$enlaceVideo"
                     style="background:#ff6b35;color:#fff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:16px;font-weight:bold;display:inline-block;">
                    Ver tutorial
                  </a>
                </div>
                <p style="font-size:13px;color:#666;margin:0;text-align:center;">
                  Recibes este email porque sigues a <strong style="color:#aaa;">$nombreProfesional</strong> en TuneFix.
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

// ═══════════════════════════════════════════════════════════════════════════
// FUNCIONES PARA EL SISTEMA DE PROVEEDORES
// ═══════════════════════════════════════════════════════════════════════════

/**
 * Notifica al admin que hay una nueva solicitud de proveedor pendiente.
 */
function enviarNotificacionAdminNuevaSolicitud(
    string $nombreEmpresa,
    string $cif,
    string $nombreResponsable,
    string $emailSolicitante
): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_FROM;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_FROM, 'Admin TuneFix');

        $enlaceAdmin = APP_URL . '/admin/index.php?page=proveedores';

        $mail->isHTML(true);
        $mail->Subject = '🏭 Nueva solicitud de proveedor: ' . $nombreEmpresa;
        $mail->Body    = emailBodyAdminNuevaSolicitud($nombreEmpresa, $cif, $nombreResponsable, $emailSolicitante, $enlaceAdmin);
        $mail->AltBody = "Nueva solicitud de proveedor de $nombreEmpresa (CIF: $cif). Responsable: $nombreResponsable <$emailSolicitante>. Revísala en: $enlaceAdmin";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer enviarNotificacionAdminNuevaSolicitud: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Notifica al proveedor que su solicitud ha sido aceptada.
 */
function enviarEmailProveedorAceptado(
    string $destinatario,
    string $nombreEmpresa,
    string $nombreResponsable
): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_FROM;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($destinatario, $nombreResponsable);

        $enlaceLogin = APP_URL . '/login.php';

        $mail->isHTML(true);
        $mail->Subject = '✅ ¡Tu solicitud como Proveedor Verificado ha sido aprobada! - TuneFix';
        $mail->Body    = emailBodyProveedorAceptado($nombreResponsable, $nombreEmpresa, $enlaceLogin);
        $mail->AltBody = "Hola $nombreResponsable, tu solicitud para $nombreEmpresa ha sido aprobada. Ya puedes acceder a tu panel en: $enlaceLogin";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer enviarEmailProveedorAceptado: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Notifica al proveedor que su solicitud ha sido rechazada.
 */
function enviarEmailProveedorRechazado(
    string $destinatario,
    string $nombreEmpresa,
    string $nombreResponsable,
    string $motivo
): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_FROM;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($destinatario, $nombreResponsable);

        $mail->isHTML(true);
        $mail->Subject = 'Actualización sobre tu solicitud de Proveedor en TuneFix';
        $mail->Body    = emailBodyProveedorRechazado($nombreResponsable, $nombreEmpresa, $motivo);
        $mail->AltBody = "Hola $nombreResponsable, lamentamos informarte que tu solicitud para $nombreEmpresa no ha podido ser aprobada. Motivo: $motivo";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer enviarEmailProveedorRechazado: ' . $mail->ErrorInfo);
        return false;
    }
}

function emailBodyAdminNuevaSolicitud(
    string $empresa, string $cif, string $responsable, string $email, string $enlace
): string {
    return <<<HTML
    <!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;background:#0f0f0f;font-family:Arial,sans-serif;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0f0f;padding:40px 0;">
        <tr><td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1a1a;border-radius:12px;overflow:hidden;">
            <tr>
              <td style="background:linear-gradient(135deg,#a4042e,#ff3c00);padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:26px;letter-spacing:2px;">TUNE<span style="color:#0f0f0f;">FIX</span></h1>
                <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:14px;">Nueva solicitud de proveedor</p>
              </td>
            </tr>
            <tr>
              <td style="padding:36px;color:#e0e0e0;">
                <p style="font-size:17px;margin:0 0 20px;">🏭 Nueva solicitud pendiente de revisión:</p>
                <table width="100%" cellpadding="8" cellspacing="0" style="background:#111;border-radius:8px;font-size:14px;">
                  <tr><td style="color:#888;width:40%;">Empresa</td><td style="color:#fff;font-weight:bold;">$empresa</td></tr>
                  <tr><td style="color:#888;">CIF/NIF</td><td style="color:#fff;">$cif</td></tr>
                  <tr><td style="color:#888;">Responsable</td><td style="color:#fff;">$responsable</td></tr>
                  <tr><td style="color:#888;">Email</td><td style="color:#fff;">$email</td></tr>
                </table>
                <div style="text-align:center;margin:28px 0;">
                  <a href="$enlace" style="background:#a4042e;color:#fff;text-decoration:none;padding:13px 32px;border-radius:8px;font-size:15px;font-weight:bold;display:inline-block;">
                    Ver solicitud en el panel
                  </a>
                </div>
              </td>
            </tr>
            <tr><td style="background:#111;padding:18px;text-align:center;">
              <p style="color:#444;font-size:12px;margin:0;">© 2026 TuneFix · Panel de administración</p>
            </td></tr>
          </table>
        </td></tr>
      </table>
    </body></html>
    HTML;
}

function emailBodyProveedorAceptado(string $responsable, string $empresa, string $enlace): string
{
    return <<<HTML
    <!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;background:#0f0f0f;font-family:Arial,sans-serif;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0f0f;padding:40px 0;">
        <tr><td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1a1a;border-radius:12px;overflow:hidden;">
            <tr>
              <td style="background:linear-gradient(135deg,#a4042e,#ff3c00);padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:26px;letter-spacing:2px;">TUNE<span style="color:#0f0f0f;">FIX</span></h1>
                <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:14px;">Proveedor Verificado ✓</p>
              </td>
            </tr>
            <tr>
              <td style="padding:40px 36px;color:#e0e0e0;">
                <p style="font-size:18px;margin:0 0 16px;">¡Enhorabuena, <strong style="color:#ff8800;">$responsable</strong>!</p>
                <p style="font-size:15px;line-height:1.7;color:#aaa;margin:0 0 24px;">
                  Tu solicitud para <strong style="color:#fff;">$empresa</strong> como <strong style="color:#ff3c00;">Proveedor Verificado</strong> en TuneFix
                  ha sido <strong style="color:#75b798;">aprobada</strong>. Ya puedes acceder a tu panel y empezar a publicar tus piezas.
                </p>
                <div style="background:#111;border-radius:10px;padding:20px;margin-bottom:24px;text-align:center;">
                  <p style="color:#888;margin:0 0 4px;font-size:13px;">Tu badge</p>
                  <p style="color:#ffc107;font-size:18px;font-weight:bold;margin:0;">🏅 Proveedor Verificado ✓</p>
                </div>
                <div style="text-align:center;margin:8px 0 32px;">
                  <a href="$enlace" style="background:#ff3c00;color:#fff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:16px;font-weight:bold;display:inline-block;">
                    Acceder a mi panel
                  </a>
                </div>
                <p style="font-size:13px;color:#555;margin:0;text-align:center;">Usa el email y la contraseña que indicaste al registrarte.</p>
              </td>
            </tr>
            <tr><td style="background:#111;padding:20px;text-align:center;">
              <p style="color:#444;font-size:12px;margin:0;">© 2026 TuneFix · Todos los derechos reservados</p>
            </td></tr>
          </table>
        </td></tr>
      </table>
    </body></html>
    HTML;
}

function emailBodyProveedorRechazado(string $responsable, string $empresa, string $motivo): string
{
    return <<<HTML
    <!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"></head>
    <body style="margin:0;padding:0;background:#0f0f0f;font-family:Arial,sans-serif;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0f0f;padding:40px 0;">
        <tr><td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1a1a;border-radius:12px;overflow:hidden;">
            <tr>
              <td style="background:linear-gradient(135deg,#555,#333);padding:30px;text-align:center;">
                <h1 style="color:#fff;margin:0;font-size:26px;letter-spacing:2px;">TUNE<span style="color:#888;">FIX</span></h1>
                <p style="color:rgba(255,255,255,0.6);margin:8px 0 0;font-size:14px;">Actualización de solicitud</p>
              </td>
            </tr>
            <tr>
              <td style="padding:40px 36px;color:#e0e0e0;">
                <p style="font-size:18px;margin:0 0 16px;">Hola, <strong>$responsable</strong></p>
                <p style="font-size:15px;line-height:1.7;color:#aaa;margin:0 0 20px;">
                  Lamentamos informarte que la solicitud de acceso para <strong style="color:#fff;">$empresa</strong>
                  no ha podido ser aprobada en este momento.
                </p>
                <div style="background:#1e1010;border:1px solid rgba(220,53,69,0.3);border-radius:10px;padding:20px;margin-bottom:24px;">
                  <p style="color:#888;margin:0 0 8px;font-size:13px;text-transform:uppercase;letter-spacing:1px;">Motivo</p>
                  <p style="color:#ff8888;margin:0;font-size:15px;line-height:1.6;">$motivo</p>
                </div>
                <p style="font-size:14px;color:#666;margin:0;">Si tienes alguna duda puedes ponerte en contacto con nosotros respondiendo a este email.</p>
              </td>
            </tr>
            <tr><td style="background:#111;padding:20px;text-align:center;">
              <p style="color:#444;font-size:12px;margin:0;">© 2026 TuneFix · Todos los derechos reservados</p>
            </td></tr>
          </table>
        </td></tr>
      </table>
    </body></html>
    HTML;
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
