<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// Aseguramos que $conn se cargue correctamente
require_once(__DIR__ . '/conexion.php');

// Validamos conexión
if (!isset($conn) || $conn->connect_error) {
    die(json_encode(["error" => "Error al conectar con la base de datos"]));
}

// 🔹 Filtros de búsqueda o categoría
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// 🔹 Consulta base
$sql = "SELECT id, nombre, categoria, descripcion, precio, imagen FROM productos WHERE 1=1";

// 🔹 Filtrar por categoría (si existe)
if (!empty($categoria) && $categoria !== 'todo') {
    $sql .= " AND categoria = '" . $conn->real_escape_string($categoria) . "'";
}

// 🔹 Filtrar por texto en nombre o descripción
if (!empty($busqueda)) {
    $busqueda = $conn->real_escape_string($busqueda);
    $sql .= " AND (nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%')";
}

// 🔹 Ejecutar consulta
$resultado = $conn->query($sql);
$productos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
}

// 🔹 Enviar respuesta JSON
echo json_encode($productos, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
