<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

include("API/conexion.php");

$usuario_id = $_SESSION["usuario_id"];
$pedido_id = $_SESSION['pedido_id'] ?? null;

if (!$pedido_id) {
    die("No hay pedido actual.");
}

// 🔹 Obtener todos los productos de este pedido
$resultado = $conn->query("
    SELECT c.*, p.imagen 
    FROM compras c 
    LEFT JOIN productos p ON c.producto = p.nombre 
    WHERE c.usuario_id='$usuario_id' AND c.pedido_id='$pedido_id'
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Compra</title>
</head>
<body>
<h1>🧾 Ticket de Compra</h1>
<table border="1">
    <tr>
        <th>Imagen</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Total</th>
        <th>Tipo de entrega</th>
        <th>Dirección</th>
    </tr>
    <?php
    while ($fila = $resultado->fetch_assoc()) {
        $imagen = !empty($fila['imagen']) ? $fila['imagen'] : 'img/default.png';
        echo "<tr>
                <td><img src='{$imagen}' width='80'></td>
                <td>{$fila['producto']}</td>
                <td>{$fila['cantidad']}</td>
                <td>\${$fila['total']}</td>
                <td>{$fila['tipo_entrega']}</td>
                <td>{$fila['direccion_entrega']}</td>
              </tr>";
    }
    ?>
</table>

<a href="mis_compras.php">📦 Ver mis compras</a>
</body>
</html>
