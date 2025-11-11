<?php
session_start();
include("API/conexion.php");

$error = "";

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);

    // Verificar si es registro o inicio
    if (isset($_POST["registrarse"])) {
        $nombre = trim($_POST["nombre"]);
        $verificar = trim($_POST["verificar"]);

        if ($contrasena === $verificar) {
            // Encriptar contraseña
            $hash = password_hash($contrasena, PASSWORD_BCRYPT);

            // Insertar nuevo usuario
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES ('$nombre', '$correo', '$hash')";
            if ($conn->query($sql)) {
                $_SESSION["usuario"] = $nombre;
                $_SESSION["usuario_id"] = $conn->insert_id;
                header("Location: index.php");
                exit;
            } else {
                $error = "⚠️ Ese correo ya está registrado.";
            }
        } else {
            $error = "Las contraseñas no coinciden.";
        }
    } elseif (isset($_POST["iniciar"])) {
        // Verificar login
        $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($contrasena, $usuario["contrasena"])) {
                $_SESSION["usuario"] = $usuario["nombre"];
                $_SESSION["usuario_id"] = $usuario["id"];
                header("Location: index.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "No existe una cuenta con ese correo.";
        }
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
            <h2>Accede a tu cuenta o regístrate</h2>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Escribe tu nombre">

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Tu contraseña" required>

            <label for="verificar">Verificar contraseña:</label>
            <input type="password" id="verificar" name="verificar" placeholder="Repite la contraseña">

            <div style="margin-top:10px;">
                <button type="submit" name="registrarse">Registrarse</button>
                <button type="submit" name="iniciar">Iniciar Sesión</button>
            </div>
        </form>

        <div class="regresar">
            <a href="index.php">← Volver a la página principal</a>
        </div>
    </main>
</body>
</html>
