<?php
require 'sesion.php';
iniciar_sesion();
requerir_login();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Otomí</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        /* Estilos para control de visibilidad y tarjetas */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .lesson {
            display: none;
            padding: 30px;
            border-radius: 12px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .lesson.active {
            display: block;
        }
        .container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .card {
            background: white;
            color: #333;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            text-align: center;
            border: 4px solid transparent;
            transition: all 0.2s ease;
            width: 130px;
        }
        .card img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .card.selected {
            border-color: #ff4ac3;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .botones {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        button {
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .comprobar {
            background-color: #4CAF50;
            color: white;
        }
        .saltar {
            background-color: #e53935;
            color: white;
        }
        .progress-container {
            max-width: 600px;
            margin: 0 auto 20px auto;
            text-align: center;
        }
        #progress-bar {
            width: 100%;
            background-color: #ddd;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
        }
        #progress-fill {
            height: 100%;
            width: 25%;
            background-color: #4CAF50;
            transition: width 0.3s ease;
        }
        .resultado {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <!-- BARRA DE PROGRESO -->
    <div class="progress-container">
        <h3 id="progress-text">Lección 1 de 4</h3>
        <div id="progress-bar">
            <div id="progress-fill"></div>
        </div>
    </div>

    <!-- LECCIÓN 1 -->
    <section id="leccion1" class="lesson active" style="background-color:#ffb9f6; color:white;">
        <h3>Nueva palabra</h3>
        <h1>¿Cuál de estos es Rosa?</h1>
        <div class="container">
            <div class="card" onclick="seleccionarCard(this, 1); reproducirAudio2();" data-correct="true">
                <img src="images/cerdo_pix.png" alt="Roxa">
                <h3>Roxa</h3> 
            </div>
            <div class="card" onclick="seleccionarCard(this, 1); reproducirAudio1();" data-correct="false">
                <img src="images/naranja_pix.png" alt="Naraxa">
                <h3>Naraxa</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 1); reproducirAudio3();" data-correct="false">
                <img src="images/cafe_pix.png" alt="Boxo">
                <h3>Boxo</h3>
            </div>
        </div>
        <div class="botones">
            <button class="comprobar" onclick="comprobarRespuesta(1)">COMPROBAR</button>
            <button class="saltar" onclick="siguienteLeccion()">SALTAR</button>
        </div>
        <p id="resultado1" class="resultado"></p>
    </section>

    <!-- LECCIÓN 2 -->
    <section id="leccion2" class="lesson" style="background-color:#e1a3ec; color:#333;">
        <h3>Nueva palabra</h3>
        <h1>¿Cuál de estos es el Café?</h1>
        <div class="container">
            <div class="card" onclick="seleccionarCard(this, 2); reproducirAudio1();" data-correct="false">
                <img src="images/naranja_pix.png" alt="Naraxa">
                <h3>Naraxa</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 2); reproducirAudio3();" data-correct="true">
                <img src="images/cafe_pix.png" alt="Boxo">
                <h3>Boxo</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 2); reproducirAudio2();" data-correct="false">
                <img src="images/verde.png" alt="verde">
                <h3>K'apaxi</h3>
            </div>
        </div>
        <div class="botones">
            <button class="comprobar" onclick="comprobarRespuesta(2)">COMPROBAR</button>
            <button class="saltar" onclick="siguienteLeccion()">SALTAR</button>
        </div>
        <p id="resultado2" class="resultado"></p>
    </section>

    <!-- LECCIÓN 3 -->
    <section id="leccion3" class="lesson" style="background-color:#edbff5; color:#333;">
        <h3>Nueva palabra</h3>
        <h1>¿Cuál de estos es un té?</h1>
        <div class="container">
            <div class="card" onclick="seleccionarCard(this, 3); reproducirAudio1();" data-correct="false">
                <img src="images/naranja_pix.png" alt="Naraxa">
                <h3>Naraxa</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 3); reproducirAudio3();" data-correct="false">
                <img src="images/cafe_pix.png" alt="Boxo">
                <h3>Boxo</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 3); reproducirAudio2();" data-correct="true">
                <img src="images/te.png" alt="te">
                <h3>uthe</h3>
            </div>
        </div>
        <div class="botones">
            <button class="comprobar" onclick="comprobarRespuesta(3)">COMPROBAR</button>
            <button class="saltar" onclick="siguienteLeccion()">SALTAR</button>
        </div>
        <p id="resultado3" class="resultado"></p>
    </section>

    <!-- LECCIÓN 4 -->
    <section id="leccion4" class="lesson" style="background-color:#c8b6ff; color:#333;">
        <h3>Nueva palabra</h3>
        <h1>Identifica Café en Otomí (Boxo)</h1>
        <div class="container"> 
            <div class="card" onclick="seleccionarCard(this, 4); reproducirAudio1();" data-correct="false">
                <img src="images/naranja_pix.png" alt="Naraxa">
                <h3>Naraxa</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 4); reproducirAudio3();" data-correct="true">
                <img src="images/cafe_pix.png" alt="cafe">
                <h3>Boxo</h3>
            </div>
            <div class="card" onclick="seleccionarCard(this, 4); reproducirAudio2();" data-correct="false">
                <img src="images/verde.png" alt="verde">
                <h3>K'apaxi</h3>
            </div>
        </div>
        <div class="botones">
            <button class="comprobar" onclick="comprobarRespuesta(4)">COMPROBAR</button>
            <button class="saltar" onclick="siguienteLeccion()">SALTAR</button>
        </div>
        <p id="resultado4" class="resultado"></p>
    </section>

    <!-- ARCHIVOS DE AUDIO -->
    <audio id="audio1" src="audio/naranxa.ogg"></audio>
    <audio id="audio2" src="audio/Roxa.ogg"></audio>
    <audio id="audio3" src="audio/boxo.ogg"></audio>

    <!-- SCRIPTS -->
    <script>
        let leccionActual = 1;
        const totalLecciones = 4;
        let puntaje = 0;
        let seleccionadas = {};
        let esperando = false;   // evita doble clic en COMPROBAR
        let finalizado = false;  // evita enviar el progreso dos veces

        function reproducirAudio1(){
            const audio = document.getElementById("audio1");
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio bloqueado por navegador", e));
        }
        function reproducirAudio2(){
            const audio = document.getElementById("audio2");
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio bloqueado por navegador", e));
        }
        function reproducirAudio3(){
            const audio = document.getElementById("audio3");
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio bloqueado por navegador", e));
        }

        function seleccionarCard(cardElement, numLeccion) {
            // Remueve la selección previa en la lección actual
            const cards = document.querySelectorAll(`#leccion${numLeccion} .card`);
            cards.forEach(c => c.classList.remove('selected'));
            
            // Marca la tarjeta seleccionada
            cardElement.classList.add('selected');
            seleccionadas[numLeccion] = cardElement;
        }

        function actualizarProgreso() {
            const porcentaje = (leccionActual / totalLecciones) * 100;
            document.getElementById("progress-fill").style.width = porcentaje + "%";
            document.getElementById("progress-text").innerText = `Lección ${leccionActual} de ${totalLecciones}`;
        }

        function siguienteLeccion() {
            // Oculta la lección actual
            document.getElementById(`leccion${leccionActual}`).classList.remove('active');
            
            if (leccionActual < totalLecciones) {
                leccionActual++;
                // Muestra la siguiente lección
                document.getElementById(`leccion${leccionActual}`).classList.add('active');
                actualizarProgreso();
            } else {
                mostrarFinal();
            }
        }

        function comprobarRespuesta(numLeccion) {
            if (esperando) return;
            const cardSeleccionada = seleccionadas[numLeccion];
            const resultadoEl = document.getElementById(`resultado${numLeccion}`);

            if (!cardSeleccionada) {
                alert("Por favor selecciona una opción antes de comprobar.");
                return;
            }

            const esCorrecta = cardSeleccionada.getAttribute("data-correct") === "true";

            if (esCorrecta) {
                puntaje += 25; // 25 puntos por lección
                esperando = true;
                resultadoEl.style.color = "#0e6251";
                resultadoEl.innerText = "¡Correcto! 🎉";
                setTimeout(() => {
                    resultadoEl.innerText = "";
                    esperando = false;
                    siguienteLeccion();
                }, 1000);
            } else {
                resultadoEl.style.color = "#78281f";
                resultadoEl.innerText = "Incorrecto. Inténtalo de nuevo o salta.";
            }
        }

        function mostrarFinal() {
            if (finalizado) return;
            finalizado = true;

            fetch("actualizar_progreso.php", {
                method: "POST",
                credentials: "same-origin",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ experienciaGanada: puntaje })
            })
            .then(async response => {
                if (response.status === 401) {
                    window.location.href = "login.html";
                    return;
                }
                const data = await response.json().catch(() => null);
                if (!data || !data.success) {
                    throw new Error((data && data.mensaje) || "Respuesta inválida del servidor");
                }
                alert("🎉 ¡Felicidades!\n\nGanaste " + puntaje + " puntos.\nTu progreso ha sido actualizado.");
                window.location.href = "index.php";
            })
            .catch(error => {
                console.error(error);
                finalizado = false;
                if (confirm("No se pudo guardar tu progreso:\n" + error.message + "\n\n¿Reintentar?")) {
                    mostrarFinal();
                } else {
                    window.location.href = "index.php";
                }
            });
        }
    </script>
</body>
</html>