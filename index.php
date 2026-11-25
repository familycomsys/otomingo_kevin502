<?php
require 'sesion.php';
iniciar_sesion();
requerir_login();

$nombre = htmlspecialchars($_SESSION['nombre'] ?? 'estudiante', ENT_QUOTES, 'UTF-8');
$xp = 0; $racha = 0; $porcentaje = 0;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require 'conexion.php';
    $stmt = $conn->prepare("SELECT experiencia, racha, porcentaje FROM progreso WHERE id_usuario = ?");
    $stmt->bind_param("i", $_SESSION['id_usuario']);
    $stmt->execute();
    if ($fila = $stmt->get_result()->fetch_assoc()) {
        $xp = (int)$fila['experiencia'];
        $racha = (int)$fila['racha'];
        $porcentaje = (int)$fila['porcentaje'];
    }
    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    // si falla la consulta, se muestran los valores en 0
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cultura Otomí - App</title>
    <style>
        /* Estilos Generales y Layout de la App */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex; /* Permite colocar la barra lateral y el contenido lado a lado */
        }

        /* <!-- ESTILOS DEL MENÚ LATERAL (SIDEBAR) --> */
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 2px solid #e5e5e5;
            padding: 30px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            color: #3c3c3c;
            font-weight: bold;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        .sidebar span {
            font-size: 0.75rem;
            color: #afafaf;
            letter-spacing: 1px;
            display: block;
            margin-top: 5px;
            margin-bottom: 30px;
        }

        .menu-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Botones estilo Duolingo dentro de la Sidebar */
        .btn-start {
            background-color: transparent;
            color: #4b4b4b;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        /* Efecto Hover interactivo */
        .btn-start:hover {
            background-color: #f7f7f7;
            border: 2px solid #e5e5e5;
            color: #130007;
        }

        /* Variante destacada para el botón "Aprender" activo */
        .menu-links li:first-child .btn-start {
            background-color: #d61a78;
            color: white;
        }
        .menu-links li:first-child .btn-start:hover {
            background-color: #940731;
        }

        .icon {
            font-size: 1.2rem;
        }

        .divider {
            height: 2px;
            background-color: #e5e5e5;
            margin: 15px 0;
        }

        /* <!-- CONTENEDOR PRINCIPAL DERECHO --> */
        .main-content {
            margin-left: 260px; /* Desplaza el contenido para que no lo tape la sidebar */
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Contenedor de Bienvenida */
        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 40px auto;
            width: 90%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .intro-p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        .main-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Menú de Navegación Superior */
        nav.top-nav {
            background-color: #d61a78;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            max-width: 800px;
            margin: 0 auto;
        }
        nav.top-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }
        nav.top-nav a {
            color: #e5d4bc;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }
        nav.top-nav a:hover {
            color: #ffffff;
        }

        /* Secciones de Contenido */
        section {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(44, 37, 32, 0.05);
            border-left: 5px solid #d61a78;
        }
        h2 {
            font-size: 2rem;
            color: #1a1512;
            margin-top: 0;
            border-bottom: 1px dashed #d1c7bd;
            padding-bottom: 10px;
            letter-spacing: 0.5px;
        }
        p {
            font-size: 1.1rem;
            color: #54473e;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .imagen {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        .imagen img {
            flex: 1;
            min-width: 250px;
            max-height: 400px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #d1c7bd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        /* Adaptación para pantallas pequeñas (Celulares) */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 2px solid #e5e5e5;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- MENÚ LATERAL (SIDEBAR) -->
    <nav class="sidebar">
        Duolingo Otomí<br>
        <span>APRENDE JUGANDO</span>
        
        <ul class="menu-links">
            <li><a href="leccion1.php" class="btn-start"><span class="icon">🏠</span>Aprender</a></li>
            <li><a href="leccion_1.html" class="btn-start"><span class="icon">🌱</span>Lecciones</a></li>
            <li><a href="vocales.php" class="btn-start"><span class="icon">🔤</span>Vocales</a></li>
            <li><a href="consonantes.php" class="btn-start"><span class="icon">🔠</span>Consonantes</a></li>
            <li><a href="perfil.php" class="btn-start"><span class="icon">👤</span>Perfil</a></li>
            <li><a href="juegos.php" class="btn-start"><span class="icon">🎮</span>Juegos</a></li>
            <li class="divider"></li>
            <li><a href="logout.php" class="btn-start"><span class="icon">🚪</span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- AREA DE CONTENIDO PRINCIPAL (DERECHA) -->
    <div class="main-content">

        <!-- Sección de Inicio -->
        <div class="container" id="inicio">
            <h1>¡Bienvenidx, <?= $nombre ?>!</h1>
            <p class="intro-p">Aprende palabras, vocabulario y sobre la maravillosa cultura Hñähñu de una manera fácil y divertida.</p>
            <p class="intro-p"><strong>⭐ <?= $xp ?> XP &nbsp;·&nbsp; 🔥 Racha: <?= $racha ?> &nbsp;·&nbsp; 📈 Progreso: <?= $porcentaje ?>%</strong></p>
            <img class="main-img" src="images/logoplaceholder.png" alt="Logo Otomí">
        </div>

        <!-- Menú de Navegación Superior -->
        <nav class="top-nav">
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#lugares">Lugares para visitar</a></li>
                <li><a href="#costumbres">Costumbres</a></li>
                <li><a href="#comida">Comida tradicional</a></li>
            </ul>
        </nav>

        <!-- Contenido de las Secciones -->
        <main>
            <!-- Sección: Lugares para visitar -->
            <section id="lugares">
                <h2>Lugares para visitar</h2>
                <p>La región otomí cuenta con imponentes legados arquitectónicos y naturales. Uno de los puntos más emblemáticos es el Centro Ceremonial Otomí, un espacio sagrado rodeado de naturaleza diseñado para revivir las antiguas ceremonias y conectar con las raíces prehispánicas.</p>
                <div class="imagen">
                    <img src="images/temoaya.jpg" alt="Centro Ceremonial Otomí">
                </div>
            </section>

            <!-- Sección: Costumbres -->
            <section id="costumbres">
                <h2>Costumbres y Tradiciones</h2>
                <p>El pueblo otomí mantiene vivas festividades de gran valor espiritual. Destacan las danzas rituales dedicadas a la agricultura, el día de muertos con coloridas ofrendas y el uso de hermosos textiles bordados a mano, conocidos como tenangos, que narran historias de su entorno natural.</p>
                <div class="imagen">
                    <img src="images/tradicion.jpg" alt="Costumbres Otomís">
                </div>
            </section>

            <!-- Sección: Comida tradicional -->
            <section id="comida">
                <h2>Comida tradicional</h2>
                <p>La gastronomía tradicional es rica en ingredientes locales extraídos del maguey y del campo. Disfruta de deliciosos platillos tradicionales como los ximbós (carne cocinada al horno de tierra), gusanos de maguey, pulque natural y tortillas hechas a mano con maíz criollo.</p>
                <div class="imagen">
                    <img src="images/ximbo.jpg" alt="Comida tradicional Otomí">
                </div>
            </section>
        </main>
    </div>

</body>
</html>