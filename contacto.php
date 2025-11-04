<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - 8VA Rebanada</title>
    <link rel="stylesheet" href="css/estilo_index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <!-- 🔹 HEADER -->
    <header>
        <div class="logo">
            <span>🍕</span>
        </div>

        <h1 class="titulo">8VA ReBaNaDa</h1>

        <div class="acciones-header">
            <div class="botones-superiores">
                <a href="formulario.html" class="btn-login">Iniciar Sesión</a>
                <button class="btn-carrito"><img src="img/carro.png" alt="carrito"></button>
            </div>
        </div>
    </header>

    <div class="botones-inferiores">
            <a href="index.php" class="btn-login">Inicio</a>
            <a href="menu.php" class="btn-login">Menu</a>
            <a href="ordena.php" class="btn-login">Ordena</a>
            <a href="contacto.php" class="btn-login">Contacto</a>

            
        </div>

    <div class="contenedor-linea">
        <div class="linea1"></div>
    </div>

    <!-- 🔹 CONTENIDO PRINCIPAL -->
    <main class="contacto">
        <h2>Contáctanos</h2>
        <p>¿Tienes dudas o comentarios? ¡Nos encantaría escucharte!</p>

        <form class="form-contacto" action="#" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" placeholder="tucorreo@ejemplo.com" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>

            <button type="submit" class="btn-enviar">Enviar</button>
        </form>
    </main>

    <!-- 🔹 PIE DE PÁGINA -->
    <footer class="pie-pagina">
        <div class="footer-izq">
            <a href="https://maps.app.goo.gl/FVRkTHBrWogSUAgc6" target="_blank">📍 Ver ubicación</a>
        </div>
        <div class="footer-der">
            <p>ALAMEDAS DE VILLAFRANCA</p>
            <p>LOMAS DE LOS CASTILLOS</p>
        </div>
    </footer>
</body>
</html>
