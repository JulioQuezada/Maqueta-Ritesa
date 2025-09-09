<?php
// enviar_correo.php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método no permitido'); }

$csrf = $_POST['csrf'] ?? '';
if (!csrf_check($csrf)) { http_response_code(403); exit('Token inválido'); }

$nombre  = trim((string)($_POST['name'] ?? ''));
$email   = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$asunto  = trim((string)($_POST['subject'] ?? ''));
$mensaje = trim((string)($_POST['message'] ?? ''));
$hp      = trim((string)($_POST['website'] ?? '')); // honeypot oculto (debe estar vacío)

if ($hp !== '') { http_response_code(200); exit('OK'); } // bot

if (!$email || $asunto === '' || $mensaje === '') {
  http_response_code(422); exit('Datos inválidos');
}
if (preg_match('/[\r\n]/', $asunto)) {
  http_response_code(400); exit('Asunto inválido');
}

$to = $_ENV['CONTACT_TO'] ?? 'contacto@tu-dominio.com';
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/plain; charset=utf-8';
$headers[] = 'From: '.$nombre.' <'.$email.'>';
$headers[] = 'Reply-To: '.$email;
$headers[] = 'X-Content-Type-Options: nosniff';

$body = "Nombre: {$nombre}\nEmail: {$email}\nAsunto: {$asunto}\n\n{$mensaje}\n";

$ok = @mail($to, $asunto, $body, implode("\r\n", $headers));
if ($ok) {
  echo 'Mensaje enviado';
} else {
  http_response_code(500);
  echo 'Error al enviar';
}

// Para mejor entregabilidad, migra a PHPMailer con SMTP autenticado.
