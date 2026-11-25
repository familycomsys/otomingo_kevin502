<?php
require 'sesion.php';
require 'menu.php';
iniciar_sesion();
requerir_login();

// [letra, cómo suena, palabra de ejemplo, significado]
$consonantes = [
    ['b',  'Como la b de «bola», suave.',                                  'be',      'ladrón'],
    ['ch', 'Como la ch de «chico». Aparece en muy pocas palabras.',        'chimänxa','gusano de elote'],
    ['d',  'Como la d de «dedo», suave.',                                  'dehe',    'agua'],
    ['f',  'Suave, con los dos labios: entre «p» y «f».',                  'fani',    'caballo'],
    ['g',  'Como la g de «gato».',                                         'gädo',    'albañil'],
    ['h',  'Aspirada, como la h del inglés «home» (no es muda).',          'hai',     'tierra'],
    ['j',  'Como la j de «jarra».',                                        'ji',      'sangre'],
    ['k',  'Como la c de «casa» o la k de «kilo».',                        'kähä',    'tuna'],
    ['l',  'Como la l de «luna». Aparece en pocas palabras.',              'lochi',   'chico'],
    ['m',  'Como la m de «mesa».',                                         'mada',    'molcajete'],
    ['n',  'Como la n de «nube».',                                         'ne',      'boca'],
    ['ñ',  'Como la ñ de «niño».',                                         'ñu',      'camino'],
    ['p',  'Como la p de «pan».',                                          'pede',    'contar'],
    ['r',  'Suave, como la r de «pero».',                                  'ra',      'el, la'],
    ['s',  'Como la s de «sol».',                                          'sani',    'peine'],
    ['t',  'Como la t de «taza».',                                         'tihi',    'correr'],
    ['th', 'Como la th del inglés «think» (la z de España en «zapato»).',  'thä',     'mazorca'],
    ['ts', 'Como la ts de «tsunami».',                                     'tsi',     'diente'],
    ['x',  'Como la sh del inglés «show».',                                'xi',      'hoja'],
    ['y',  'Como la y de «yo».',                                           'yo',      'vela'],
    ['z',  'Como la z del inglés «zoo».',                                  'zoni',    'llorar'],
    ['’',  'Saltillo: una pausa breve en la garganta, como en «uh-oh».',   'ts’udi',  'puerco'],
];

function tarjetas(array $lista): void {
    foreach ($lista as [$letra, $sonido, $palabra, $significado]) {
        $h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
        echo '<div class="letra-card">';
        echo '<div class="letra">' . $h($letra) . '</div>';
        echo '<div class="sonido">' . $h($sonido) . '</div>';
        echo '<div class="ejemplo">';
        echo '<span class="palabra">' . $h($palabra) . '</span>';
        echo '<span class="significado">' . $h($significado) . '</span>';
        echo '</div></div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consonantes - Duolingo Otomí</title>
    <link rel="stylesheet" href="estilo_letras.css">
</head>
<body>
    <?php menu_lateral('consonantes'); ?>

    <div class="main-content">
        <div class="pagina">
            <h1>🔠 Consonantes en hñähñu</h1>
            <p class="intro">El hñähñu (otomí del Valle del Mezquital) se escribe con <strong>22 consonantes</strong>, incluido el saltillo (’).</p>

            <div class="nota">
                <strong>Ojo:</strong> la <b>h</b> sí se pronuncia (aspirada) y la <b>j</b> suena como la j del español.
                Además, la <b>u</b> antes de otra vocal suena como «w»: <b>ua</b> (pie), <b>uada</b> (maguey).
            </div>

            <div class="barra-herr">
                <h2>Todas las consonantes</h2>
                <button class="btn-practica" id="btnPractica" type="button">🙈 Modo práctica</button>
            </div>
            <div class="grid-letras"><?php tarjetas($consonantes); ?></div>

            <p class="fuentes">
                Fuentes: norma de escritura del hñähñu (INALI) y Diccionario del hñähñu (otomí) del Valle del Mezquital (ILV, 2010).
                Otras variantes del otomí (ñätho, ñañho, yühü…) se escriben y pronuncian distinto.
            </p>
        </div>
    </div>

    <script src="letras.js"></script>
</body>
</html>