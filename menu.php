<?php
// menu.php — menú lateral compartido. Uso: <?php menu_lateral('vocales');
function menu_lateral(string $activo = ''): void {
    $items = [
        'aprender'    => ['leccion1.php',    '🏠', 'Aprender'],
        'lecciones'   => ['leccion_1.html', '🌱', 'Lecciones'],
        'vocales'     => ['vocales.php',     '🔤', 'Vocales'],
        'consonantes' => ['consonantes.php', '🔠', 'Consonantes'],
        'perfil'      => ['perfil.php',      '👤', 'Perfil'],
        'juegos'      => ['juegos.php',      '🎮', 'Juegos'],
    ];

    echo '<nav class="sidebar">';
    echo '<a class="marca" href="index.php">Duolingo Otomí</a>';
    echo '<span class="lema">APRENDE JUGANDO</span>';
    echo '<ul class="menu-links">';
    foreach ($items as $clave => [$href, $icono, $texto]) {
        $clase = 'btn-start' . ($clave === $activo ? ' activo' : '');
        echo "<li><a href=\"$href\" class=\"$clase\"><span class=\"icon\">$icono</span>$texto</a></li>";
    }
    echo '<li class="divider"></li>';
    echo '<li><a href="logout.php" class="btn-start"><span class="icon">🚪</span>Cerrar sesión</a></li>';
    echo '</ul></nav>';
}