<?php
require 'sesion.php';
require 'menu.php';
iniciar_sesion();
requerir_login();

// [letra, cómo suena, palabra de ejemplo, significado]
$orales = [
    ['a',        'Como la a de «casa».',                              'agi',          'enterrar'],
    ['e',        'Como la e de «mesa».',                              'eke',          'peinar'],
    ['i',        'Como la i de «sí».',                                'ixi',          'durazno'],
    ['o',        'Como la o de «sol».',                               'ofo',          'escribir'],
    ['u',        'Como la u de «luna».',                              'uni',          'dar'],
    ["a\u{331}", 'Vocal abierta, como la a del inglés «cat».',        null,           null],
    ["e\u{331}", 'Una e más abierta, como en el inglés «bed».',       "e\u{331}i",    'tirar'],
    ["o\u{331}", 'Como la u del inglés «cup».',                       "o\u{331}de",   'oír'],
    ["u\u{331}", 'Entre «i» y «u», sin redondear los labios.',        "u\u{331}ni",   'lastimar'],
];

$nasales = [
    ['ä', 'La a, dejando salir el aire también por la nariz.', 'ähä',  'dormir'],
    ['ë', 'La e, con el aire por la nariz.',                    'thëdi', 'visión'],
    ['ï', 'La i, con el aire por la nariz (aparece en pocas palabras).', 'uï', 'soñar'],
    ['ö', 'La o, con el aire por la nariz.',                    'höö',  'sí'],
    ['ü', 'La u, con el aire por la nariz.',                    'ü',    'sal'],
];

function tarjetas(array $lista): void {
    foreach ($lista as [$letra, $sonido, $palabra, $significado]) {
        $h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
        echo '<div class="letra-card">';
        echo '<div class="letra">' . $h($letra) . '</div>';
        echo '<div class="sonido">' . $h($sonido) . '</div>';
        echo '<div class="ejemplo">';
        if ($palabra) {
            echo '<span class="palabra">' . $h($palabra) . '</span>';
            echo '<span class="significado">' . $h($significado) . '</span>';
        } else {
            echo '<span class="pendiente">Ejemplo próximamente</span>';
        }
        echo '</div></div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocales - Duolingo Otomí</title>
    <link rel="stylesheet" href="estilo_letras.css">
</head>
<body>
    <?php menu_lateral('vocales'); ?>

    <div class="main-content">
        <div class="pagina">
            <h1>🔤 Vocales en hñähñu</h1>
            <p class="intro">El hñähñu (otomí del Valle del Mezquital) tiene <strong>14 vocales</strong>: 9 orales y 5 nasales.</p>

            <div class="nota">
                <strong>¡Ojo con la nariz!</strong> En las vocales nasales el aire sale también por la nariz, y eso puede cambiar el significado de la palabra:
                <div class="pares">
                    <span class="par"><b>thede</b> reírse · <b>thëdi</b> visión</span>
                    <span class="par"><b>heke</b> partir · <b>hëki</b> perforar</span>
                </div>
            </div>

            <div class="barra-herr">
                <h2>Vocales orales</h2>
                <button class="btn-practica" id="btnPractica" type="button">🙈 Modo práctica</button>
            </div>
            <div class="grid-letras"><?php tarjetas($orales); ?></div>

            <div class="barra-herr"><h2>Vocales nasales</h2></div>
            <div class="grid-letras"><?php tarjetas($nasales); ?></div>

            <p class="fuentes">
                Las vocales con una rayita debajo (a̱, e̱, o̱, u̱) son las orales abiertas o centrales.<br>
                Fuentes: norma de escritura del hñähñu (INALI) y Diccionario del hñähñu (otomí) del Valle del Mezquital (ILV, 2010).
                Otras variantes del otomí (ñätho, ñañho, yühü…) se escriben y pronuncian distinto.
            </p>
        </div>
    </div>

    <script src="letras.js"></script>
</body>
</html>