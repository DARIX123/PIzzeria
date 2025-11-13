<?php
// 1️⃣ Conexión a la base de datos
$servername = "localhost";
$username = "root";       // Usuario de MySQL
$password_db = "";        // Contraseña de MySQL (vacía )
$dbname = "db_qr";        // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password_db, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// 2️⃣ Obtener datos del formulario
$nombre = $_POST['nombre'];
$apodo = $_POST['apodo'];
$correo = $_POST['correo'];
$password = $_POST['password'];
$confirmar = $_POST['confirmar'];
$nacimiento = $_POST['nacimiento'];

// 3️⃣ Validar que las contraseñas coincidan
if ($password !== $confirmar) {
    die("Las contraseñas no coinciden. <a href='index.html'>Volver</a>");
}

// 4️⃣ Hashear la contraseña
$password_hashed = password_hash($password, PASSWORD_BCRYPT);

// 5️⃣ Generar un ID único para el usuario
$id_usuario = uniqid("user_");

// 6️⃣ Insertar datos en la base de datos
$stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, apodo, correo, password, nacimiento) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $id_usuario, $nombre, $apodo, $correo, $password_hashed, $nacimiento);

if ($stmt->execute()) {
    // 7️⃣ Redirigir a la página que mostrará el QR
    header("Location: mostrar_qr.php?id=$id_usuario");
    exit();
} else {
    echo "Error al registrar usuario: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
