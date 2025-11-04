<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto - 8VA Rebanada</title>
    <link rel="stylesheet" href="css/estilo_menu.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 🔹 Fuente formal -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        /* 🔹 Configuración general */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #000;
        }

        /* 🔹 Header */
        header {
            background-color: #fff;
            color: #000;
            text-align: center;
            padding: 15px 0;
            border-bottom: 2px solid #ddd;
        }

        /* 🔹 Zona central con imagen de fondo */
        main {
            background-image: url("img/fondooo.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            z-index: 0;
            color: white;
            padding: 40px 0 80px;
            min-height: 60vh;
            text-align: center;
        }

        main::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        /* 🔹 Título formal centrado */
        .titulo-tienda {
            font-family: 'Playfair Display', serif;
            font-size: 4em;
            font-weight: 700;
            color: #ffffff;
            margin-top: 60px;
            text-align: center;
            letter-spacing: 2px;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.7);
        }

        /* 🔹 Footer */
        footer {
            background-color: #fff;
            color: #000;
            text-align: center;
            padding: 20px 0;
            border-top: 2px solid #ddd;
        }

        /* 🔹 Botones */
        .botones-inferiores {
            background-color: #fff;
            text-align: center;
            padding: 15px 0;
        }

        .btn-login {
            background-color: white;
            border: 2px solid red;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: red;
            font-weight: bold;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: red;
            color: white;
        }

        /* 🔹 Botón activo (página actual) */
        .btn-activo {
            background-color: red;
            color: white;
        }
    </style>
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

    <!-- 🔹 BOTONES -->
    <div class="botones-inferiores">
        <a href="index.html" class="btn-login">Inicio</a>
        <a href="menu.html" class="btn-login">Menú</a>
        <a href="ordena.html" class="btn-login btn-activo">Ordena</a> <!-- 🔸 Botón activo -->
        <a href="contacto.html" class="btn-login">Contacto</a>
    </div>

    <!-- 🔹 SECCIÓN CON FONDO -->
    <main>
        <h2 class="titulo-tienda">ELIGE TU TIENDA</h2>
        <div class="contenedor-preferencia">
    <h3>¿CÓMO PREFIERES RECIBIR TU PIZZA?</h3>
    <hr>
   <div class="contenedor-preferencia">
    <h3>¿CÓMO PREFIERES RECIBIR TU PIZZA?</h3>
    <hr>
    <div class="contenido-preferencia">
        <div class="opciones-entrega">
            <div class="opcion">
                <button class="btn-opcion">
                    <img src="img/domicilio.jpg" alt="Domicilio">
                </button>
                <p>A DOMICILIO</p>
            </div>
            <div class="opcion">
                <button class="btn-opcion">
                    <img src="img/tienda.jpg" alt="En tienda">
                </button>
                <p>EN TIENDA</p>
            </div>
        <div class="botones-inferiores">
            <a href="index.php" class="btn-login">Inicio</a>
            <a href="menu.php" class="btn-login">Menu</a>
            <a href="ordena.php" class="btn-login">Ordena</a>
            <a href="contacto.php" class="btn-login">Contacto</a>

            
        </div>
    </div>
</div>


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

