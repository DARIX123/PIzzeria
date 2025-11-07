<?php
session_start();
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"]);
    $correo = trim($_POST["correo"]);
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_BCRYPT);

    // Registrar nuevo usuario
    if (isset($_POST["registrar"])) {
        $sql = "INSERT INTO usuarios (usuario, correo, contrasena) VALUES ('$usuario', '$correo', '$contrasena')";
        if ($conexion->query($sql)) {
            $mensaje = "Registro exitoso. ¡Inicia sesión!";
        } else {
            $mensaje = "Error: " . $conexion->error;
        }
    }

    // Iniciar sesión
    if (isset($_POST["login"])) {
        $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
        $resultado = $conexion->query($sql);
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            if (password_verify($_POST["contrasena"], $fila["contrasena"])) {
                $_SESSION["usuario"] = $fila["usuario"];
                $_SESSION["usuario_id"] = $fila["id"];
                header("Location: index.php");
                exit;
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            $mensaje = "No existe una cuenta con ese correo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión / Registro</title>
    <link rel="stylesheet" href="css/estilo_index.css">
</head>
<body>
    <h2>Inicio de Sesión / Registro</h2>
    <p style="color:red;"><?php echo $mensaje; ?></p>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Nombre de usuario" required><br>
        <input type="email" name="correo" placeholder="Correo electrónico" required><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br>

        <button type="submit" name="registrar">Registrarse</button>
        <button type="submit" name="login">Iniciar Sesión</button>
    </form>
</body>
</html>
