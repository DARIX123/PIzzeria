<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];
$resultado = $conexion->query("SELECT * FROM compras WHERE usuario_id='$usuario_id' ORDER BY fecha_compra DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras</title>
</head>
<body>
    <h1>Mis Compras</h1>
    <table border="1" cellpadding="8">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Entrega</th>
            <th>Fecha</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila['producto']) ?></td>
                <td><?= $fila['cantidad'] ?></td>
                <td>$<?= $fila['total'] ?></td>
                <td><?= $fila['tipo_entrega'] ?></td>
                <td><?= $fila['fecha_compra'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
