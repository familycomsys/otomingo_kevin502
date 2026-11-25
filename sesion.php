<?php
// sesion.php — utilidades de sesión compartidas por todas las páginas PHP

function iniciar_sesion(bool $recordar = false): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $vida = 60 * 60 * 24 * 30; // 30 días
    ini_set('session.gc_maxlifetime', (string)$vida);
    session_set_cookie_params([
        'lifetime' => $recordar ? $vida : 0, // 0 = se cierra al cerrar el navegador
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Si no hay sesión iniciada, manda al login
function requerir_login(): void {
    if (empty($_SESSION['id_usuario'])) {
        header('Location: login.html');
        exit;
    }
}