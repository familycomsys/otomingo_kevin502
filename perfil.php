<?php
require 'sesion.php';
iniciar_sesion();
requerir_login();

$idUsuario = (int)$_SESSION['id_usuario'];
$u = null;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require 'conexion.php';
    $stmt = $conn->prepare("
        SELECT u.nombre, u.email, u.fecha_registro,
            COALESCE(p.experiencia, 0) AS experiencia,
            COALESCE(p.racha, 0)       AS racha,
            COALESCE(p.porcentaje, 0)  AS porcentaje
        FROM usuario u
        LEFT JOIN progreso p ON p.id_usuario = u.id_usuario
        WHERE u.id_usuario = ?
    ");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    $errorBD = $e->getMessage();
}

if (!$u && !isset($errorBD)) { // el usuario ya no existe en la BD
    header('Location: logout.php');
    exit;
}

// Fecha de ingreso en español, p. ej. "9 de noviembre de 2026"
function fecha_es(?string $fecha): string {
    if (!$fecha || !($t = strtotime($fecha))) return '—';
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    return date('j', $t) . ' de ' . $meses[(int)date('n', $t) - 1] . ' de ' . date('Y', $t);
}

// Foto de perfil (si existe en fotos/usuario_ID.*)
$foto = null;
$archivos = glob(__DIR__ . "/fotos/usuario_{$idUsuario}.*");
if ($archivos) {
    $foto = 'fotos/' . basename($archivos[0]) . '?v=' . filemtime($archivos[0]);
}

$h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');

$mensajes = [
    'ok'     => ['ok',    '✅ Foto actualizada'],
    'grande' => ['error', '❌ La imagen pesa más de 2 MB'],
    'tipo'   => ['error', '❌ Solo se permiten imágenes JPG, PNG o WEBP'],
    'error'  => ['error', '❌ No se pudo subir la foto, inténtalo de nuevo'],
];
$aviso = $mensajes[$_GET['foto'] ?? ''] ?? null;

$nombre     = $u['nombre'] ?? ($_SESSION['nombre'] ?? '');
$inicial    = mb_strtoupper(mb_substr($nombre, 0, 1)) ?: '?';
$porcentaje = (int)($u['porcentaje'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - Duolingo Otomí</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; display: flex; }

        /* Menú lateral (igual que en index.php) */
        .sidebar { width: 260px; background: #fff; height: 100vh; position: fixed; top: 0; left: 0;
            border-right: 2px solid #e5e5e5; padding: 30px 20px; box-sizing: border-box;
            display: flex; flex-direction: column; color: #3c3c3c; font-weight: bold; font-size: 1.3rem; }
        .sidebar a.marca { color: inherit; text-decoration: none; }
        .sidebar span.lema { font-size: .75rem; color: #afafaf; letter-spacing: 1px; display: block; margin: 5px 0 30px; }
        .menu-links { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
        .btn-start { color: #4b4b4b; text-decoration: none; display: flex; align-items: center; gap: 15px;
            padding: 12px 20px; font-size: 16px; border-radius: 12px; border: 2px solid transparent; transition: all .2s; }
        .btn-start:hover { background: #f7f7f7; border-color: #e5e5e5; }
        .btn-start.activo { background: #d61a78; color: #fff; }
        .btn-start.activo:hover { background: #940731; }
        .icon { font-size: 1.2rem; }
        .divider { height: 2px; background: #e5e5e5; margin: 15px 0; }

        /* Contenido */
        .main-content { margin-left: 260px; flex: 1; padding: 20px; box-sizing: border-box; }
        .perfil { max-width: 700px; margin: 30px auto; background: #fff; border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.08); border-top: 5px solid #d61a78; padding: 30px; }
        .cabecera { display: flex; align-items: center; gap: 25px; flex-wrap: wrap; }
        .avatar-wrap { position: relative; width: 120px; height: 120px; cursor: pointer; flex-shrink: 0; }
        .avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #d61a78; display: block; }
        .avatar.inicial { background: #f9d3e6; color: #d61a78; font-size: 3.2rem; font-weight: bold;
            display: flex; align-items: center; justify-content: center; }
        .cambiar { position: absolute; bottom: 2px; right: 2px; background: #d61a78; color: #fff; width: 34px; height: 34px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; border: 3px solid #fff; }
        .datos h1 { margin: 0 0 5px; font-size: 1.8rem; color: #333; text-align: left; }
        .datos p { margin: 0; color: #666; font-size: 1rem; }
        .aviso { margin-top: 15px; padding: 10px 14px; border-radius: 8px; font-weight: bold; }
        .aviso.ok { background: #e6f6ea; color: #1b6b32; }
        .aviso.error { background: #fdeaea; color: #a32020; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 30px; }
        .stat { border: 2px solid #e5e5e5; border-radius: 12px; padding: 15px; text-align: center; }
        .stat .num { font-size: 1.6rem; font-weight: bold; color: #333; }
        .stat .etq { font-size: .85rem; color: #888; margin-top: 4px; }
        .barra-wrap { margin-top: 25px; }
        .barra-wrap .etq { font-weight: bold; color: #555; margin-bottom: 6px; display: flex; justify-content: space-between; }
        .barra { background: #ddd; height: 14px; border-radius: 7px; overflow: hidden; }
        .barra div { height: 100%; background: #d61a78; transition: width .4s ease; }
        .ingreso { margin-top: 25px; color: #666; text-align: center; }
        .error-bd { color: #a32020; font-weight: bold; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { position: relative; width: 100%; height: auto; border-right: none; border-bottom: 2px solid #e5e5e5; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    <nav class="sidebar">
        <a class="marca" href="index.php">Duolingo Otomí</a>
        <span class="lema">APRENDE JUGANDO</span>
        <ul class="menu-links">
            <li><a href="leccion1.php" class="btn-start"><span class="icon">🏠</span>Aprender</a></li>
            <li><a href="leccion_1.html" class="btn-start"><span class="icon">🌱</span>Lecciones</a></li>
            <li><a href="vocales.php" class="btn-start"><span class="icon">🔤</span>Vocales</a></li>
            <li><a href="consonantes.php" class="btn-start"><span class="icon">🔠</span>Consonantes</a></li>
            <li><a href="perfil.php" class="btn-start activo"><span class="icon">👤</span>Perfil</a></li>
            <li><a href="juegos.php" class="btn-start"><span class="icon">🎮</span>Juegos</a></li>
            <li class="divider"></li>
            <li><a href="logout.php" class="btn-start"><span class="icon">🚪</span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="perfil">
        <?php if (isset($errorBD)): ?>
            <p class="error-bd">No se pudo cargar tu perfil: <?= $h($errorBD) ?></p>
        <?php else: ?>

            <div class="cabecera">
                <form id="formFoto" action="subir_foto.php" method="POST" enctype="multipart/form-data">
                    <label class="avatar-wrap" title="Cambiar foto">
                        <?php if ($foto): ?>
                            <img class="avatar" src="<?= $h($foto) ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <div class="avatar inicial"><?= $h($inicial) ?></div>
                        <?php endif; ?>
                        <span class="cambiar">📷</span>
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" hidden
                            onchange="document.getElementById('formFoto').submit()">
                    </label>
                </form>
                <div class="datos">
                    <h1><?= $h($u['nombre']) ?></h1>
                    <p>✉️ <?= $h($u['email']) ?></p>
                </div>
            </div>

            <?php if ($aviso): ?>
                <div class="aviso <?= $aviso[0] ?>"><?= $aviso[1] ?></div>
            <?php endif; ?>

            <div class="stats">
                <div class="stat"><div class="num">⭐ <?= (int)$u['experiencia'] ?></div><div class="etq">Experiencia (XP)</div></div>
                <div class="stat"><div class="num">🔥 <?= (int)$u['racha'] ?></div><div class="etq">Racha</div></div>
                <div class="stat"><div class="num">📈 <?= $porcentaje ?>%</div><div class="etq">Porcentaje</div></div>
            </div>

            <div class="barra-wrap">
                <div class="etq"><span>Progreso del curso</span><span><?= $porcentaje ?>%</span></div>
                <div class="barra"><div style="width: <?= $porcentaje ?>%"></div></div>
            </div>

            <p class="ingreso">📅 Te uniste el <strong><?= $h(fecha_es($u['fecha_registro'])) ?></strong></p>

        <?php endif; ?>
        </div>
    </div>

</body>
</html>