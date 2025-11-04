<?php
session_start();

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contrasena = $_POST["contrasena"];
    $verificar = $_POST["verificar"];

    if ($contrasena === $verificar) {
        // Guardamos el nombre en sesión
        $_SESSION["usuario"] = $nombre;
        // Redirigimos a la página principal
        header("Location: index.php");
        exit;
    } else {
        $error = "Las contraseñas no coinciden.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - 8VA Rebanada</title>
    <link rel="stylesheet" href="css/estilo_formulario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <header>
        <h1>🍕 8VA ReBaNaDa</h1>
        <p>Inicia sesión o crea tu cuenta</p>
    </header>

    <main>
        <form class="form-login" method="POST" action="">
            <h2>Formulario de Inicio de Sesión</h2>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Escribe tu nombre" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Crea una contraseña" required>

            <label for="verificar">Verificar contraseña:</label>
            <input type="password" id="verificar" name="verificar" placeholder="Repite la contraseña" required>

            <button type="submit">Registrarse</button>
        </form>

        <div class="regresar">
            <a href="index.php">← Volver a la página principal</a>
        </div>
    </main>
</body>
</html>
