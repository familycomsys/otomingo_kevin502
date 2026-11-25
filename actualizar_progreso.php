<?php
require 'sesion.php';
iniciar_sesion();

header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (empty($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "mensaje" => "Debes iniciar sesión"]);
    exit;
}

$datos = json_decode(file_get_contents("php://input"), true);
$puntajeGanado = (int)($datos['experienciaGanada'] ?? 0);
$puntajeGanado = max(0, min($puntajeGanado, 100)); // tope por lección, el cliente no es de fiar
$idUsuario = (int)$_SESSION['id_usuario'];

try {
    require 'conexion.php'; // define $conn

    // ¿El usuario ya tiene fila en progreso?
    $stmt = $conn->prepare("SELECT 1 FROM progreso WHERE id_usuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $existe = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($existe) {
        $stmt = $conn->prepare("
            UPDATE progreso
            SET experiencia = experiencia + ?,
                racha = racha + 1,
                porcentaje = LEAST(porcentaje + 5, 100)
            WHERE id_usuario = ?
        ");
        $stmt->bind_param("ii", $puntajeGanado, $idUsuario);
    } else {
        $stmt = $conn->prepare("
            INSERT INTO progreso (id_usuario, experiencia, racha, porcentaje)
            VALUES (?, ?, 1, 5)
        ");
        $stmt->bind_param("ii", $idUsuario, $puntajeGanado);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(["success" => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "mensaje" => "Error en el servidor: " . $e->getMessage()]);
}