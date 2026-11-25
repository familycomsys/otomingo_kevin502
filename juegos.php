<?php
require 'sesion.php';
require 'menu.php';
iniciar_sesion();
requerir_login();

// Para agregar el tercer juego: pon su archivo en 'url' (por ejemplo 'juego3.html')
// y cambia 'disponible' a true.
$juegos = [
    [
        'emoji'       => '✦',
        'titulo'      => 'NTHËNI — Las salas del saber',
        'etiquetas'   => 'AVENTURA · VOCABULARIO · 3 DIFICULTADES',
        'descripcion' => 'Explora salas, aprende palabras en otomí, reúne monedas y encuentra reliquias.',
        'url'         => 'juego1.html',
        'disponible'  => true,
    ],
    [
        'emoji'       => '🌗',
        'titulo'      => 'Eco de dos mundos',
        'etiquetas'   => 'MINI RPG · BULLET HELL',
        'descripcion' => 'Una pequeña aventura entre dos realidades: esquiva proyectiles y descubre palabras en hñähñu.',
        'url'         => 'juego2.html',
        'disponible'  => true,
    ],
    [
        'emoji'       => '🌱',
        'titulo'      => 'Jardín hñähñu',
        'etiquetas'   => 'DEFIENDE LAS PLANTAS, ROGUELIKE',
        'descripcion' => 'Defiende las flores con plantas, mejora el jardín y aprende otomí mientras juegas.',
        'url'         => 'juego3.html',
        'disponible'  => true,
    ],
];

$h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juegos - Duolingo Otomí</title>
    <link rel="stylesheet" href="estilo_letras.css">
    <link rel="stylesheet" href="estilo_juegos.css">
</head>
<body>
    <?php menu_lateral('juegos'); ?>

    <div class="main-content">
        <div class="pagina">
            <h1>🎮 Juegos</h1>
            <p class="intro">Elige un juego y practica el otomí divirtiéndote.</p>

            <div class="grid-juegos">
            <?php foreach ($juegos as $j): ?>
                <div class="juego-card<?= $j['disponible'] ? '' : ' pronto' ?>">
                    <div class="emoji"><?= $h($j['emoji']) ?></div>
                    <h2><?= $h($j['titulo']) ?></h2>
                    <div class="etiquetas"><?= $h($j['etiquetas']) ?></div>
                    <p><?= $h($j['descripcion']) ?></p>
                    <?php if ($j['disponible']): ?>
                        <a class="btn-jugar" href="<?= $h($j['url']) ?>">▶ Jugar</a>
                    <?php else: ?>
                        <span class="btn-jugar deshabilitado">Próximamente</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>