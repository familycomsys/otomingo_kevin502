<?php
require 'sesion.php';
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$pass     = $_POST['contrasena'] ?? '';
$recordar = isset($_POST['recordar']);

// Buscamos solo por email; la contraseña se verifica en PHP porque está cifrada
$stmt = $conn->prepare("SELECT id_usuario, nombre, email, contrasena FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

$ok = false;
if ($usuario) {
    $guardada = $usuario['contrasena'];

    if (password_verify($pass, $guardada)) {
        $ok = true;
    } elseif (empty(password_get_info($guardada)['algo']) && hash_equals($guardada, $pass)) {
        // Usuario antiguo con contraseña sin cifrar: se acepta y se cifra ahora
        $ok = true;
        $nuevo = password_hash($pass, PASSWORD_DEFAULT);
        $up = $conn->prepare("UPDATE usuario SET contrasena = ? WHERE id_usuario = ?");
        $up->bind_param("si", $nuevo, $usuario['id_usuario']);
        $up->execute();
        $up->close();
    }
}

$conn->close();

if (!$ok) {
    header('Location: login.html?error=1');
    exit;
}

iniciar_sesion($recordar);
session_regenerate_id(true);

$_SESSION['usuario']    = $usuario['email'];
$_SESSION['id_usuario'] = (int)$usuario['id_usuario'];
$_SESSION['nombre']     = $usuario['nombre'];

header('Location: index.php');
exit;