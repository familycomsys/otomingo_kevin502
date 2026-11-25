<?php
header("Content-Type: application/json; charset=utf-8");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function responder(bool $estado, string $mensaje): void {
    echo json_encode(["estado" => $estado, "mensaje" => $mensaje]);
    exit;
}

try {
    include("conexion.php");

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        responder(false, "❌ No se recibieron datos válidos");
    }

    $nombre           = trim($data['nombre'] ?? '');
    $email            = trim($data['email'] ?? '');
    $contrasena_plana = $data['contrasena'] ?? '';
    $fecha_nac        = $data['fecha_nac'] ?? null;
    $pais             = trim($data['pais'] ?? '');
    $nivel            = (int)($data['nivel'] ?? 1);

    if ($nombre === '' || $email === '' || $contrasena_plana === '') {
        responder(false, "❌ Faltan datos obligatorios");
    }

    // Email repetido
    $stmt = $conn->prepare("SELECT 1 FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        responder(false, "❌ Ya existe una cuenta con ese correo");
    }
    $stmt->close();

    $contrasena     = password_hash($contrasena_plana, PASSWORD_DEFAULT);
    $fecha_registro = date("Y-m-d");

    $res  = $conn->query("SELECT COALESCE(MAX(id_usuario), 0) AS maximo FROM usuario");
    $fila = $res->fetch_assoc();
    $nuevo_id = ($fila['maximo'] < 99) ? 99 : $fila['maximo'] + 1;

    $stmt = $conn->prepare("INSERT INTO usuario (id_usuario, nombre, email, contrasena, fecha_registro, fecha_nac, pais, nivel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssi", $nuevo_id, $nombre, $email, $contrasena, $fecha_registro, $fecha_nac, $pais, $nivel);
    $stmt->execute();
    $stmt->close();

    // Fila de progreso inicial (si falla, actualizar_progreso.php la crea después)
    try {
        $stmt = $conn->prepare("INSERT INTO progreso (id_usuario, experiencia, racha, porcentaje) VALUES (?, 0, 0, 0)");
        $stmt->bind_param("i", $nuevo_id);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {
        // se ignora a propósito
    }

    $conn->close();
    responder(true, "✅ Usuario registrado correctamente");
} catch (Throwable $e) {
    responder(false, "❌ Error al registrar usuario: " . $e->getMessage());
}