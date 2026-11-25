<?php
require 'sesion.php';
iniciar_sesion();
requerir_login();

function volver(string $codigo): void {
    header('Location: perfil.php?foto=' . $codigo);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['foto'])) {
    volver('error');
}

$f = $_FILES['foto'];

if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE || $f['size'] > 2 * 1024 * 1024) {
    volver('grande');
}
if ($f['error'] !== UPLOAD_ERR_OK) {
    volver('error');
}

// Verificamos que de verdad sea una imagen y de un tipo permitido
$permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$info = @getimagesize($f['tmp_name']);
$mime = $info['mime'] ?? '';
if (!isset($permitidos[$mime])) {
    volver('tipo');
}

$dir = __DIR__ . '/fotos';
if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
    volver('error');
}

// Una sola foto por usuario: se borra la anterior
$id = (int)$_SESSION['id_usuario'];
foreach (glob("$dir/usuario_$id.*") ?: [] as $vieja) {
    @unlink($vieja);
}

if (!move_uploaded_file($f['tmp_name'], "$dir/usuario_$id." . $permitidos[$mime])) {
    volver('error');
}

volver('ok');