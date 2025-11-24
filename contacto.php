<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <link rel="stylesheet" href="css/estilo_index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/i18next@22.4.9/i18next.min.js"></script>
    <script src="https://unpkg.com/i18next-browser-languagedetector@6.1.4/i18nextBrowserLanguageDetector.min.js"></script>
    <script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js"></script>
</head>

<body>
    <!-- 🔹 HEADER -->
    <header>
            <?php session_start(); ?>


        <div class="logo">
            <span>🍕</span>
        </div>

        <h1 class="titulo">8VA ReBaNaDa</h1>

        <div class="acciones-header">
            <div class="botones-superiores">
                <?php if (isset($_SESSION["usuario"])): ?>
                <span class="nombre-usuario">👋 Hola, <?php echo htmlspecialchars($_SESSION["usuario"]); ?></span>
                <a href="logout.php" class="btn-login">Cerrar sesión</a>
            <?php else: ?>
                <a href="formulario.php" class="btn-login">Iniciar Sesión</a>
            <?php endif; ?>
                <button class="btn-carrito">
                    <img src="img/carro.png" alt="carrito">
                </button>
            </div>
        </div>
    </header>

    <!-- 🔹 BOTONES INFERIORES -->
    <div class="botones-inferiores">
        <a href="index.php" class="btn-login" data-i18n="inicio">Inicio</a>
        <a href="menu.php" class="btn-login" data-i18n="menu">Menú</a>
        <a href="ordena.php" class="btn-login" data-i18n="ordena">Ordena</a>
        <a href="contacto.php" class="btn-login activo" data-i18n="contacto">Contacto</a>
    </div>

    <div class="contenedor-linea">
        <div class="linea1"></div>
    </div>

    <!-- 🔹 CONTENIDO PRINCIPAL -->
    <main class="contacto" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; width: 90%; max-width: 900px; margin: 40px auto;">

        <!-- 📞 IZQUIERDA -->
        <section class="servicio" style="border: 2px solid #b22222; border-radius: 10px; padding: 20px;">
            <h3 style="color:#0a3d62;">Estamos a tu servicio ☎️</h3>
            <ul style="list-style:none; padding:10px 0;">
                <li>477 294 3183</li>
                <li>477 522 3650</li>
                <li>477 574 2766</li>
                <li>479 482 0055</li>
            </ul>

            <div class="horarios" style="margin-top:15px;">
                <h4 style="color:#0a3d62;">Horarios</h4>
                <p>Pedidos en línea: 11:00am – 11:00pm</p>
                <p>Entrega a domicilio: hasta 11:30pm</p>
                <p>Soporte en línea: 12:00pm – 9:00pm</p>
            </div>
        </section>

        <!-- 💬 DERECHA -->
        <section class="sugerencias" style="border: 2px solid #b22222; border-radius: 10px; padding: 20px;">
            <h3 style="color:#0a3d62;">Queremos escucharte</h3>
            <p>¿Tienes alguna sugerencia, comentario o queja sobre nuestro servicio o productos?  
            Por favor compártela con nosotras.</p>

            <textarea placeholder="Escribe aquí..." style="width:100%; height:120px; border:1.5px solid #b22222; border-radius:8px; padding:10px; resize:none; margin-top:10px;"></textarea>
        </section>
    </main>

    <!-- 🔹 PIE DE PÁGINA (igual que antes) -->
    <footer class="pie-pagina">
        <div class="footer-izq">
            <a href="https://maps.app.goo.gl/FVRkTHBrWogSUAgc6" target="_blank">📍 Ver ubicación</a>
        </div>
        <div class="footer-der">
            <p>ALAMEDAS DE VILLAFRANCA</p>
            <p>LOMAS DE LOS CASTILLOS</p>
        </div>
    </footer>
    
<script src="js/traduccion.js"></script>
</body>
</html>

