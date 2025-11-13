<?php
// mostrar_qr.php

// Ruta correcta a la librería (usa __DIR__ para evitar problemas de rutas)
require_once __DIR__ . '/phpqrcode/qrlib.php';

// Obtener el userId (mejor que venga por GET desde registro.php)
$userId = isset($_GET['id']) ? trim($_GET['id']) : null;
if (!$userId) {
    die("Error: falta el id del usuario.");
}

// Sanitizar (solo para usar en nombres de archivo; no para DB)
$safeId = preg_replace('/[^A-Za-z0-9_\-]/', '', $userId);

// URL pública (pon aquí la URL que te dé ngrok)
$ngrokHost = 'https://multilobular-guarded-michelle.ngrok-free.dev';

// Apuntar a la página de verificación que lee `id` (IMPORTANTE: no a la raíz)
$qrUrl = $ngrokHost . '/verificar?id=' . urlencode($userId);

// Carpeta donde guardar el QR (ruta relativa al script)
$dir = __DIR__ . '/qrcodes/';
if (!file_exists(filename: $dir)) {
    mkdir($dir, 0777, true);
}

// Nombre del archivo QR
$filename = $dir . 'qr_user_' . $safeId . '.png';

// Generar el QR (archivo PNG)
QRcode::png($qrUrl, $filename, QR_ECLEVEL_L, 4);

// Redirigir al HTML visual para mostrar el QR
// Pasamos tanto el archivo como el id para que la vista sepa a quién pertenece
header("Location: mostrar_qr_visual.php?archivo=" . urlencode(basename($filename)) . "&id=" . urlencode($userId));
exit;
