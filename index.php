<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://unpkg.com/i18next@22.4.9/i18next.min.js"></script>
    <script src="https://unpkg.com/i18next-browser-languagedetector@6.1.4/i18nextBrowserLanguageDetector.min.js"></script>
    <script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js"></script>

    <meta charset="UTF-8">
    <title>Pizzeria</title>
    <link rel="stylesheet" href="css/estilo_index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<!-- MODAL DE CONFIGURACIÓN -->
<div id="modal-config" class="modal-config">
    <div class="modal-content animar-modal">
        <h2> Configuración</h2>

        <div class="config-opcion">
            <span> Idioma:</span>
            <select id="select-idioma">
                <option value="es">Español</option>
                <option value="en">English</option>
                <option value="fr">Français</option>
                <option value="it">Italiano</option>
                <option value="zh">中文</option>
                <option value="pt">Português</option>
            </select>
        </div>

        <div class="config-opcion">
            <span> Modo oscuro:</span>
            <label class="switch">
                <input type="checkbox" id="modo-oscuro">
                <span class="slider"></span>
            </label>
        </div>

        <div class="config-opcion">
            <span> Modo lectura:</span>
            <label class="switch">
                <input type="checkbox" id="modo-lectura">
                <span class="slider"></span>
            </label>
        </div>

        <button id="cerrar-config" class="btn-cerrar">Cerrar</button>
    </div>
</div>


<body>
    <header>
        <?php session_start(); ?>

        <div class="logo">
            <span>🍕</span>
        </div>

        <h1 class="titulo">8VA ReBaNaDa</h1>

        <!-- Contenedor de la parte derecha -->
        <div class="botones-superiores">
            <button id="btn-config" class="btn-config">⋮</button>
    <?php if (isset($_SESSION["usuario"])): ?>
        <span class="nombre-usuario">👋 Hola, <?php echo htmlspecialchars($_SESSION["usuario"]); ?></span>
        <a href="logout.php" class="btn-login">Cerrar sesión</a>
    <?php else: ?>
        <a href="formulario.php" class="btn-login">Iniciar Sesión</a>
    <?php endif; ?>

    <button class="btn-carrito"><img src="img/carro.png" alt="carrito"></button>
    

</div>

        </div>
    </header>

    <main>
        <div class="botones-inferiores">
            <a href="index.php" class="btn-login" data-i18n="inicio">Inicio</a>
            <a href="menu.php" class="btn-login" data-i18n="menu">Menu</a>
            <a href="ordena.php" class="btn-login" data-i18n="ordena">Ordena</a>
            <a href="contacto.php" class="btn-login" data-i18n="contacto">Contacto</a>

        </div>
        <section class="hero">

            <div class="texto-hero">
                <h1 data-i18n="bienvenida">Aquí la pizza no se comparte, se conquista. Bienvenidos A la 8VA Rebanada</h1>
            </div>

            <div class="linea"></div>

            <div class="video-hero">
                <video autoplay muted loop playsinline>
                    <source src="img/pizza_video.mp4" type="video/mp4">
                    Tu navegador no soporta videos HTML5.
                </video>
            </div>
        </section>
    </main>

    <div class="slide">
        <input class="slide-open" type="radio" id="slide-1" name="slide" aria-hidden="true" hidden="" checked="checked">
        <input class="slide-open" type="radio" id="slide-2" name="slide" aria-hidden="true" hidden="">
        <input class="slide-open" type="radio" id="slide-3" name="slide" aria-hidden="true" hidden="">
        <input class="slide-open" type="radio" id="slide-4" name="slide" aria-hidden="true" hidden="">
        <input class="slide-open" type="radio" id="slide-5" name="slide" aria-hidden="true" hidden="">

        <div class="slide-inner">
            <div class="slide-item">
                <img src="img/pizza-hawaiana.jpg" alt="Diapositiva 1: Embedding API">
            </div>
            <div class="slide-item">
                <img src="img/pizza_mexicana.jpg" alt="Diapositiva 2: PostgreSQL Copy">
            </div>
            <div class="slide-item">
                <img src="img/pizza_peperoni.jpg" alt="Diapositiva 3: Guardar CSV">
            </div>
            <div class="slide-item">
                <img src="img/pizza_4q.jpg" alt="Diapositiva 4: Placeholder Rojo">
            </div>
            <div class="slide-item">
                <img src="img/pizza_vegetariana.jpg" alt="Diapositiva 5: Placeholder Verde">
            </div>
        </div>

        <ol class="slide-indicador">
            <li><label for="slide-1" class="slide-circulo">•</label></li>
            <li><label for="slide-2" class="slide-circulo">•</label></li>
            <li><label for="slide-3" class="slide-circulo">•</label></li>
            <li><label for="slide-4" class="slide-circulo">•</label></li>
            <li><label for="slide-5" class="slide-circulo">•</label></li>
        </ol>
    </div>

    <!-- 🔹 PIE DE PÁGINA AGREGADO -->
    <footer class="pie-pagina">
        <div class="footer-izq">
            <a href="https://maps.app.goo.gl/FVRkTHBrWogSUAgc6" target="_blank">📍 Ver ubicación</a>
        </div>
        <div class="footer-der">
            <p>ALAMEDAS DE VILLAFRANCA</p>
            <p>LOMAS DE LOS CASTILLOS</p>
        </div>
    </footer>

    <script src="js/carrusel.js"></script>
    <script src="js/traduccion.js"></script>

</body>

</html>


   