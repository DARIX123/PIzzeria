<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://unpkg.com/i18next@22.4.9/i18next.min.js"></script>
    <script src="https://unpkg.com/i18next-browser-languagedetector@6.1.4/i18nextBrowserLanguageDetector.min.js"></script>
    <script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js"></script>
    <meta charset="UTF-8">
    <title>Contacto - 8VA Rebanada</title>
    <link rel="stylesheet" href="css/estilo_ordena.css">
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
    background-position: center top;
    background-attachment: scroll; /* 🔹 Ya no está fijo */
    margin-top: 50px; /* 🔹 Esto baja toda la imagen respecto a los botones */
    position: relative;
    z-index: 0;
    color: white;
    padding: 30px 0 30px;
    min-height: 55vh;
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
        
        

     
    </style>
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
                
                <button class="btn-carrito"><img src="img/carro.png" alt="carrito"></button>
                <select id="select-idioma">
    <option value="es">Español</option>
    <option value="en">English</option>
    <option value="fr">Français</option>
    <option value="it">Italiano</option>
    <option value="zh">中文</option>
    <option value="pt">Português</option>
</select>
            </div>
        </div>
    </header>

    <!-- 🔹 BOTONES -->
    <div class="botones-inferiores">
        <a href="index.php" class="btn-login" data-i18n="inicio">Inicio</a>
        <a href="menu.php" class="btn-login" data-i18n="menu">Menú</a>
        <a href="ordena.php" class="btn-login " data-i18n="ordena">Ordena</a> <!-- 🔸 Botón activo -->
        <a href="contacto.php" class="btn-login" data-i18n="contacto">Contacto</a>
    </div>

    <!-- 🔹 SECCIÓN CON FONDO -->
    <main>
        <h2 class="titulo-tienda" data-i18n="titulo-tienda">ELIGE TU TIENDA</h2>
        <div class="contenedor-preferencia">
    <h3 data-i18n="contenedor-preferencia">¿CÓMO PREFIERES RECIBIR TU PIZZA?</h3>
    <hr>
   <div class="contenedor-preferencia">
    
    <hr>
    <div class="contenido-preferencia">
        <div class="opciones-entrega">
            <div class="opcion">
              <a href="domicilio.php">
    <button class="btn-opcion">
        <img src="img/domicilio.jpg" alt="Domicilio">
    </button>
</a>

                <p data-i18n="opcion"> A DOMICILIO</p>
            </div>
            <div class="opcion">
                <button class="btn-opcion">
                    <img src="img/tienda.jpg" alt="En tienda">
                </button>
                <p data-i18n="opcion1">EN TIENDA</p>
            </div>
        

            
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
    
<script src="js/traduccionordena.js"></script>
</body>

</html>

